import subprocess
import time
import urllib.request
import json
import websocket
import sys

sys.stdout.reconfigure(encoding='utf-8')

def test_html_page(url, test_js):
    print(f"\n--- TESTING STATIC HTML PAGE: {url} ---", flush=True)
    chrome_path = r"C:\Program Files\Google\Chrome\Application\chrome.exe"
    proc = subprocess.Popen([
        chrome_path,
        "--headless=new",
        "--remote-debugging-port=9233",
        "--remote-allow-origins=*",
        "--disable-gpu",
        "--no-sandbox",
        url
    ])

    time.sleep(2.0)
    try:
        resp = urllib.request.urlopen("http://127.0.0.1:9233/json")
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
    # 1. INDEX.HTML
    test_html_page("http://sanix-tool.local/index.html", """
    JSON.stringify({
      title: document.title,
      hasHero: !!document.querySelector('.hero-title'),
      toolCardsCount: document.querySelectorAll('.tool-card').length
    })
    """)

    # 2. IMAGE COMPRESSOR HTML
    test_html_page("http://sanix-tool.local/tools/image/compressor.html", """
    JSON.stringify({
      title: document.title,
      hasDropzone: !!document.getElementById('compressorDropzone'),
      hasReady: !!document.getElementById('compressorReady'),
      hasOptions: !!document.getElementById('compressorOptions')
    })
    """)

    # 3. BACKGROUND REMOVER HTML
    test_html_page("http://sanix-tool.local/tools/image/bg-remover.html", """
    JSON.stringify({
      title: document.title,
      hasDropzone: !!document.getElementById('bgDropzone'),
      hasEditorCanvas: !!document.getElementById('bgEditorCanvas')
    })
    """)

    # 4. ID PHOTO HTML
    test_html_page("http://sanix-tool.local/tools/image/id-photo.html", """
    JSON.stringify({
      title: document.title,
      hasDropzone: !!document.getElementById('idDropzone'),
      hasA4Canvas: !!document.getElementById('a4Canvas')
    })
    """)

    # 5. IMAGE TO PDF HTML
    test_html_page("http://sanix-tool.local/tools/image/image-to-pdf.html", """
    JSON.stringify({
      title: document.title,
      hasDropzone: !!document.getElementById('pdfDropzone'),
      hasOptions: !!document.getElementById('pdfOptions')
    })
    """)

if __name__ == "__main__":
    run()
