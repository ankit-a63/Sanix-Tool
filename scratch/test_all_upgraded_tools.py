import subprocess
import time
import urllib.request
import json
import os
import sys
import websocket

sys.stdout.reconfigure(encoding='utf-8')

def test_tool(url, test_js):
    print(f"\n--- TESTING TOOL: {url} ---", flush=True)
    chrome_path = r"C:\Program Files\Google\Chrome\Application\chrome.exe"
    proc = subprocess.Popen([
        chrome_path,
        "--headless=new",
        "--remote-debugging-port=9231",
        "--remote-allow-origins=*",
        "--disable-gpu",
        "--no-sandbox",
        url
    ])

    time.sleep(2.0)
    try:
        resp = urllib.request.urlopen("http://127.0.0.1:9231/json")
        targets = json.loads(resp.read().decode())
        page = [t for t in targets if t.get("type") == "page"][0]
        ws_url = page["webSocketDebuggerUrl"]

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
        cdp("Runtime.evaluate", {"expression": "window.alert = function(m) { console.log('ALERT:', m); };"})

        res = cdp("Runtime.evaluate", {"expression": test_js})
        val = res.get("result", {}).get("result", {}).get("value")
        print("RESULT:", val, flush=True)

        ws.close()
        return True
    finally:
        try:
            proc.kill()
        except:
            pass

def run():
    # 1. RESIZER
    resizer_js = """
    (function() {
      const pngBytes = new Uint8Array([137,80,78,71,13,10,26,10,0,0,0,13,73,72,68,82,0,0,0,16,0,0,0,16,8,6,0,0,0,31,243,255,97,0,0,0,25,73,68,65,84,56,141,99,252,207,192,192,205,192,196,192,192,192,4,3,19,0,0,39,74,2,1,118,58,212,140,0,0,0,0,73,69,78,68,174,66,96,130]);
      const file = new File([pngBytes], "test-sample.png", { type: "image/png" });
      if (window.handleResizerFile) window.handleResizerFile(file);
      return JSON.stringify({
        hasDropzone: !!document.getElementById('resizerDropzone'),
        hasReady: !!document.getElementById('resizerReady'),
        hasOptions: !!document.getElementById('resizerOptions'),
        hasResult: !!document.getElementById('resizerResult')
      });
    })()
    """
    test_tool("http://sanix-tool.local/tools/image/resizer.php", resizer_js)

    # 2. BG REMOVER
    bg_js = """
    (function() {
      const pngBytes = new Uint8Array([137,80,78,71,13,10,26,10,0,0,0,13,73,72,68,82,0,0,0,16,0,0,0,16,8,6,0,0,0,31,243,255,97,0,0,0,25,73,68,65,84,56,141,99,252,207,192,192,205,192,196,192,192,192,4,3,19,0,0,39,74,2,1,118,58,212,140,0,0,0,0,73,69,78,68,174,66,96,130]);
      const file = new File([pngBytes], "test-sample.png", { type: "image/png" });
      if (window.handleBgFile) window.handleBgFile(file);
      return JSON.stringify({
        hasDropzone: !!document.getElementById('bgDropzone'),
        hasReady: !!document.getElementById('bgReady'),
        hasEditorCanvas: !!document.getElementById('bgEditorCanvas')
      });
    })()
    """
    test_tool("http://sanix-tool.local/tools/image/bg-remover.php", bg_js)

    # 3. ID PHOTO
    id_js = """
    (function() {
      const pngBytes = new Uint8Array([137,80,78,71,13,10,26,10,0,0,0,13,73,72,68,82,0,0,0,16,0,0,0,16,8,6,0,0,0,31,243,255,97,0,0,0,25,73,68,65,84,56,141,99,252,207,192,192,205,192,196,192,192,192,4,3,19,0,0,39,74,2,1,118,58,212,140,0,0,0,0,73,69,78,68,174,66,96,130]);
      const file = new File([pngBytes], "test-sample.png", { type: "image/png" });
      if (window.handleIdFile) window.handleIdFile(file);
      return JSON.stringify({
        hasDropzone: !!document.getElementById('idDropzone'),
        hasStudio: !!document.getElementById('idEditorStudio'),
        hasA4Canvas: !!document.getElementById('a4Canvas')
      });
    })()
    """
    test_tool("http://sanix-tool.local/tools/image/id-photo.php", id_js)

    # 4. IMAGE TO PDF
    pdf_js = """
    (function() {
      const pngBytes = new Uint8Array([137,80,78,71,13,10,26,10,0,0,0,13,73,72,68,82,0,0,0,16,0,0,0,16,8,6,0,0,0,31,243,255,97,0,0,0,25,73,68,65,84,56,141,99,252,207,192,192,205,192,196,192,192,192,4,3,19,0,0,39,74,2,1,118,58,212,140,0,0,0,0,73,69,78,68,174,66,96,130]);
      const file = new File([pngBytes], "test-sample.png", { type: "image/png" });
      if (window.handlePdfFiles) window.handlePdfFiles([file]);
      return JSON.stringify({
        hasDropzone: !!document.getElementById('pdfDropzone'),
        hasReady: !!document.getElementById('pdfReady'),
        hasOptions: !!document.getElementById('pdfOptions')
      });
    })()
    """
    test_tool("http://sanix-tool.local/tools/image/image-to-pdf.php", pdf_js)

if __name__ == "__main__":
    run()
