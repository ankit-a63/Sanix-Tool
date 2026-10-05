import subprocess
import time
import urllib.request
import json
import websocket
import sys

sys.stdout.reconfigure(encoding='utf-8')

def check_navbar():
    chrome_path = r"C:\Program Files\Google\Chrome\Application\chrome.exe"
    proc = subprocess.Popen([
        chrome_path,
        "--headless=new",
        "--remote-debugging-port=9232",
        "--remote-allow-origins=*",
        "--disable-gpu",
        "--no-sandbox",
        "http://sanix-tool.local/"
    ])

    time.sleep(2.0)
    try:
        resp = urllib.request.urlopen("http://127.0.0.1:9232/json")
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

        cdp("Runtime.enable")
        res = cdp("Runtime.evaluate", {
            "expression": "document.body.innerText.includes('Log In') || document.body.innerText.includes('Get Started')"
        })
        has_login_text = res.get("result", {}).get("result", {}).get("value")
        print("Navbar contains Log In/Register text:", has_login_text, flush=True)

        ws.close()
    finally:
        try:
            proc.kill()
        except:
            pass

if __name__ == "__main__":
    check_navbar()
