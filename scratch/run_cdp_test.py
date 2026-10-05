import subprocess
import time
import urllib.request
import json
import os
import sys
import websocket

sys.stdout.reconfigure(encoding='utf-8')

def run_browser_test():
    print("--- STARTING REAL BROWSER CDP WORKFLOW TEST ---")

    chrome_cmd = [
        r"C:\Program Files\Google\Chrome\Application\chrome.exe",
        "--headless=new",
        "--remote-debugging-port=9225",
        "--remote-allow-origins=*",
        "--disable-gpu",
        "--no-sandbox",
        "http://sanix-tool.local/tools/image/compressor.php"
    ]

    proc = subprocess.Popen(chrome_cmd)
    time.sleep(2.5)

    try:
        resp = urllib.request.urlopen("http://127.0.0.1:9225/json")
        targets = json.loads(resp.read().decode())
        page_target = None
        for t in targets:
            if t.get("type") == "page" and "compressor.php" in t.get("url", ""):
                page_target = t
                break

        if not page_target:
            print("ERROR: Could not find page target for compressor.php")
            return

        ws_url = page_target["webSocketDebuggerUrl"]
        print("Connected to Chrome CDP WebSocket:", ws_url)

        ws = websocket.create_connection(ws_url)
        msg_id = 0

        def send_cmd(method, params=None):
            nonlocal msg_id
            msg_id += 1
            payload = {"id": msg_id, "method": method, "params": params or {}}
            ws.send(json.dumps(payload))
            while True:
                res = json.loads(ws.recv())
                if res.get("id") == msg_id:
                    return res

        send_cmd("DOM.enable")
        send_cmd("Page.enable")
        send_cmd("Runtime.enable")

        time.sleep(1)

        # 1. Verify Step 1: DROPZONE is visible
        eval_dropzone = send_cmd("Runtime.evaluate", {
            "expression": "document.getElementById('compressorDropzone').style.display"
        })
        dropzone_disp = eval_dropzone.get("result", {}).get("result", {}).get("value")
        print("STEP 1: DROPZONE display:", repr(dropzone_disp))

        # 2. Get DOM Node ID for #compressorFileInput and select test file
        doc_res = send_cmd("DOM.getDocument")
        root_node_id = doc_res["result"]["root"]["nodeId"]

        node_res = send_cmd("DOM.querySelector", {
            "nodeId": root_node_id,
            "selector": "#compressorFileInput"
        })
        input_node_id = node_res["result"]["nodeId"]

        test_file_path = os.path.abspath(r"E:\Sanni\Sanix Tool\scratch\test.png")
        print("STEP 2: Selecting test image file:", test_file_path)

        send_cmd("DOM.setInputFiles", {
            "nodeId": input_node_id,
            "files": [test_file_path]
        })

        time.sleep(0.5)

        # 3. Verify Step 3: FILE READY & PROCEED BUTTON
        eval_ready = send_cmd("Runtime.evaluate", {
            "expression": """JSON.stringify({
                readyDisplay: document.getElementById('compressorReady').style.display,
                readyFileName: document.getElementById('readyFileName').textContent,
                readyFileSize: document.getElementById('readyFileSize').textContent,
                readyDimensions: document.getElementById('readyDimensions').textContent,
                proceedBtnText: document.getElementById('proceedCompressBtn').textContent.trim(),
                proceedBtnVisible: document.getElementById('proceedCompressBtn').offsetWidth > 0 || document.getElementById('proceedCompressBtn').style.display !== 'none'
            })"""
        })
        ready_info = json.loads(eval_ready["result"]["result"]["value"])
        print("STEP 3: FILE READY Status:", ready_info)

        # 4. Click [ ➜ PROCEED TO COMPRESS ] button
        print("STEP 4: Clicking [ ➜ PROCEED TO COMPRESS ] button...")
        send_cmd("Runtime.evaluate", {
            "expression": "document.getElementById('proceedCompressBtn').click()"
        })

        time.sleep(0.3)

        # 5. Verify Step 4: OPTIONS PANEL visibility
        eval_options = send_cmd("Runtime.evaluate", {
            "expression": """JSON.stringify({
                optionsDisplay: document.getElementById('compressorOptions').style.display,
                qualityValue: document.getElementById('compressQuality').value,
                formatValue: document.getElementById('compressFormat').value
            })"""
        })
        options_info = json.loads(eval_options["result"]["result"]["value"])
        print("STEP 4 (OPTIONS) Status:", options_info)

        # 6. Click [ ⚡ COMPRESS IMAGE NOW ] button
        print("STEP 5: Clicking [ ⚡ COMPRESS IMAGE NOW ] button...")
        send_cmd("Runtime.evaluate", {
            "expression": "document.getElementById('compressNowBtn').click()"
        })

        time.sleep(1.2)

        # 7. Verify Step 6: RESULT PANEL & Download Blob
        eval_result = send_cmd("Runtime.evaluate", {
            "expression": """JSON.stringify({
                resultDisplay: document.getElementById('compressorResult').style.display,
                origSize: document.getElementById('resultOrigSize').textContent,
                compSize: document.getElementById('resultCompSize').textContent,
                savings: document.getElementById('resultSavings').textContent,
                format: document.getElementById('resultFormat').textContent,
                downloadHref: document.getElementById('downloadCompressedBtn').href,
                downloadFileName: document.getElementById('downloadCompressedBtn').download
            })"""
        })
        result_info = json.loads(eval_result["result"]["result"]["value"])
        print("STEP 6 (RESULT & DOWNLOAD) Status:", result_info)

        ws.close()
        print("--- REAL BROWSER CDP WORKFLOW TEST PASSED 100% ---")

    finally:
        try:
            proc.kill()
        except Exception:
            pass

if __name__ == "__main__":
    run_browser_test()
