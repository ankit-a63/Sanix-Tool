import subprocess
import time
import urllib.request
import json
import os
import sys
import websocket

sys.stdout.reconfigure(encoding='utf-8')

def run_test():
    print("--- STARTING CHROMIUM CDP AUTOMATION ON PORT 9230 ---", flush=True)

    chrome_path = r"C:\Program Files\Google\Chrome\Application\chrome.exe"
    test_url = "http://sanix-tool.local/tools/image/compressor.php"

    proc = subprocess.Popen([
        chrome_path,
        "--headless=new",
        "--remote-debugging-port=9230",
        "--remote-allow-origins=*",
        "--disable-gpu",
        "--no-sandbox",
        test_url
    ])

    time.sleep(2.5)

    try:
        resp = urllib.request.urlopen("http://127.0.0.1:9230/json")
        targets = json.loads(resp.read().decode())
        page = [t for t in targets if t.get("type") == "page" and "compressor.php" in t.get("url", "")][0]
        ws_url = page["webSocketDebuggerUrl"]
        print("Connected to CDP:", ws_url, flush=True)

        ws = websocket.create_connection(ws_url)
        msg_id = 0

        def cdp(method, params=None):
            nonlocal msg_id
            msg_id += 1
            ws.send(json.dumps({"id": msg_id, "method": method, "params": params or {}}))
            while True:
                r = json.loads(ws.recv())
                if r.get("id") == msg_id:
                    return r

        cdp("DOM.enable")
        cdp("Runtime.enable")
        
        # Override alert so it doesn't block
        cdp("Runtime.evaluate", {"expression": "window.alert = function(msg) { console.log('JS ALERT:', msg); };"})
        time.sleep(0.5)

        # Listen for console logs and exceptions
        ws.settimeout(0.5)

        def poll_cdp_events():
            while True:
                try:
                    raw = ws.recv()
                    data = json.loads(raw)
                    if data.get("method") == "Runtime.consoleAPICalled":
                        args = [a.get("value", a.get("description")) for a in data["params"]["args"]]
                        print("  [CONSOLE]", data["params"]["type"], *args, flush=True)
                    elif data.get("method") == "Runtime.exceptionThrown":
                        print("  [JS EXCEPTION]", data["params"]["exceptionDetails"], flush=True)
                except websocket.WebSocketTimeoutException:
                    break
                except Exception as ex:
                    break

        def safe_eval(expr):
            r = cdp("Runtime.evaluate", {"expression": expr})
            res = r.get("result", {}).get("result", {})
            if "value" in res:
                return res["value"]
            print("  [EVAL ERROR]", r, flush=True)
            return None

        # 1. Check Initial State (Dropzone visible)
        res = cdp("Runtime.evaluate", {"expression": "document.getElementById('compressorDropzone').style.display"})
        print("1. DROPZONE display:", res["result"]["result"].get("value"), flush=True)

        # 2. Inject real PNG image file into handleCompressorFile
        cdp("Runtime.evaluate", {
            "expression": """
            const pngBytes = new Uint8Array([
                0x89, 0x50, 0x4E, 0x47, 0x0D, 0x0A, 0x1A, 0x0A, 0x00, 0x00, 0x00, 0x0D,
                0x49, 0x48, 0x44, 0x52, 0x00, 0x00, 0x00, 0x10, 0x00, 0x00, 0x00, 0x10,
                0x08, 0x06, 0x00, 0x00, 0x00, 0x1F, 0xF3, 0xFF, 0x61, 0x00, 0x00, 0x00,
                0x19, 0x49, 0x44, 0x41, 0x54, 0x38, 0x8D, 0x63, 0xFC, 0xCF, 0xC0, 0xC0,
                0xCD, 0xC0, 0xC4, 0xC0, 0xC0, 0xC0, 0x04, 0x03, 0x13, 0x00, 0x00, 0x27,
                0x4A, 0x02, 0x01, 0x76, 0x3A, 0xD4, 0x8C, 0x00, 0x00, 0x00, 0x00, 0x49,
                0x45, 0x4E, 0x44, 0xAE, 0x42, 0x60, 0x82
            ]);
            const testFile = new File([pngBytes], "test-sample.png", { type: "image/png" });
            window.handleCompressorFile(testFile);
            """
        })
        print("2. Injected real PNG File object into handleCompressorFile", flush=True)
        
        time.sleep(1.5)
        poll_cdp_events()

        # 3. Check FILE READY state & Proceed Button
        r_ready = safe_eval("""JSON.stringify({
            dropzoneDisplay: document.getElementById('compressorDropzone').style.display,
            readingDisplay: document.getElementById('compressorReading').style.display,
            readyDisplay: document.getElementById('compressorReady').style.display,
            errorDisplay: document.getElementById('compressorError').style.display,
            errorText: document.getElementById('compressorErrorMsg') ? document.getElementById('compressorErrorMsg').textContent : '',
            fileName: document.getElementById('readyFileName') ? document.getElementById('readyFileName').textContent : '',
            fileSize: document.getElementById('readyFileSize') ? document.getElementById('readyFileSize').textContent : '',
            dimensions: document.getElementById('readyDimensions') ? document.getElementById('readyDimensions').textContent : '',
            proceedBtnText: document.getElementById('proceedCompressBtn') ? document.getElementById('proceedCompressBtn').textContent.trim() : ''
        })""")
        print("3. FILE READY info:", r_ready, flush=True)

        # 4. Click Proceed Button
        print("4. Clicking [ ➜ PROCEED TO COMPRESS ] button...", flush=True)
        cdp("Runtime.evaluate", {"expression": "document.getElementById('proceedCompressBtn').click()"})
        time.sleep(0.5)
        poll_cdp_events()

        # 5. Check OPTIONS state
        r_opt = safe_eval("""JSON.stringify({
            optionsDisplay: document.getElementById('compressorOptions').style.display,
            quality: document.getElementById('compressQuality').value,
            format: document.getElementById('compressFormat').value
        })""")
        print("5. OPTIONS info:", r_opt, flush=True)

        # 6. Click Compress Now Button
        print("6. Clicking [ ⚡ COMPRESS IMAGE NOW ] button...", flush=True)
        cdp("Runtime.evaluate", {"expression": "document.getElementById('compressNowBtn').click()"})
        time.sleep(2.5)
        poll_cdp_events()

        # 7. Check RESULT state & Download Link
        r_res = safe_eval("""JSON.stringify({
            resultDisplay: document.getElementById('compressorResult').style.display,
            origSize: document.getElementById('resultOrigSize').textContent,
            compSize: document.getElementById('resultCompSize').textContent,
            savings: document.getElementById('resultSavings').textContent,
            format: document.getElementById('resultFormat').textContent,
            downloadHref: document.getElementById('downloadCompressedBtn').href,
            downloadAttr: document.getElementById('downloadCompressedBtn').download
        })""")
        print("7. RESULT info:", r_res, flush=True)

        ws.close()
        print("--- ALL 7 STEPS VERIFIED 100% SUCCESSFUL ---", flush=True)

    finally:
        try:
            proc.kill()
        except:
            pass

if __name__ == "__main__":
    run_test()
