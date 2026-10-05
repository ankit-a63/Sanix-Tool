/**
 * SANIX TOOL - SANI Interactive Virtual Assistant Engine
 */

class SaniAssistant {
  constructor() {
    this.speechBubble = null;
    this.speechText = null;
    this.pupilLeft = null;
    this.pupilRight = null;
    this.eyebrowLeft = null;
    this.eyebrowRight = null;
    this.mouth = null;
    this.currentEmotion = 'idle';
    this.speechTimeout = null;
    this.isBlinking = false;

    this.init();
  }

  init() {
    document.addEventListener('DOMContentLoaded', () => {
      this.bindElements();
      if (!this.pupilLeft) return;

      this.startBlinkTimer();
      this.bindMouseTracking();
      this.bindToolEvents();

      // Initial Greeting
      setTimeout(() => {
        const path = window.location.pathname;
        if (path.includes('image')) {
          this.say("Ready to enhance and convert your images!", "happy");
        } else if (path.includes('pdf')) {
          this.say("Need to merge or split PDFs? I'm here to help!", "greeting");
        } else if (path.includes('developer') || path.includes('json')) {
          this.say("Developer tools ready! What are we building today?", "thinking");
        } else {
          this.say("Hi there! Welcome to Sanix Tool!", "greeting", 4500);
        }
      }, 1000);
    });
  }

  bindElements() {
    this.speechBubble = document.getElementById('sani-speech-bubble');
    this.speechText = document.getElementById('sani-speech-text');
    this.pupilLeft = document.getElementById('sani-pupil-left');
    this.pupilRight = document.getElementById('sani-pupil-right');
    this.eyebrowLeft = document.getElementById('sani-eyebrow-left');
    this.eyebrowRight = document.getElementById('sani-eyebrow-right');
    this.mouth = document.getElementById('sani-mouth');
  }

  say(message, emotion = 'happy', duration = 4000) {
    if (!this.speechBubble || !this.speechText) return;

    this.setEmotion(emotion);
    this.speechText.textContent = message;
    this.speechBubble.classList.add('active');

    if (this.speechTimeout) clearTimeout(this.speechTimeout);
    this.speechTimeout = setTimeout(() => {
      this.speechBubble.classList.remove('active');
      this.setEmotion('idle');
    }, duration);
  }

  setEmotion(emotion) {
    this.currentEmotion = emotion;
    if (!this.mouth || !this.eyebrowLeft || !this.eyebrowRight) return;

    const mouthPaths = {
      idle: "M 94 122 Q 100 126 106 122",
      happy: "M 90 120 Q 100 132 110 120",
      thinking: "M 92 124 L 108 122",
      surprised: "M 95 120 A 5 6 0 1 0 105 120 A 5 6 0 1 0 95 120",
      success: "M 88 118 Q 100 136 112 118",
      error: "M 92 126 Q 100 118 108 126",
      greeting: "M 90 120 Q 100 130 110 120"
    };

    const eyebrowPathsLeft = {
      idle: "M 70 88 Q 80 84 90 88",
      happy: "M 70 85 Q 80 80 90 85",
      thinking: "M 70 82 Q 80 88 90 86",
      surprised: "M 70 80 Q 80 75 90 80",
      success: "M 70 84 Q 80 78 90 84",
      error: "M 70 88 Q 80 94 90 90"
    };

    const eyebrowPathsRight = {
      idle: "M 110 88 Q 120 84 130 88",
      happy: "M 110 85 Q 120 80 130 85",
      thinking: "M 110 86 Q 120 88 130 82",
      surprised: "M 110 80 Q 120 75 130 80",
      success: "M 110 84 Q 120 78 130 84",
      error: "M 110 90 Q 120 94 130 88"
    };

    if (mouthPaths[emotion]) this.mouth.setAttribute('d', mouthPaths[emotion]);
    if (eyebrowPathsLeft[emotion]) this.eyebrowLeft.setAttribute('d', eyebrowPathsLeft[emotion]);
    if (eyebrowPathsRight[emotion]) this.eyebrowRight.setAttribute('d', eyebrowPathsRight[emotion]);
  }

  bindMouseTracking() {
    window.addEventListener('mousemove', (e) => {
      if (this.isBlinking || !this.pupilLeft || !this.pupilRight) return;

      const avatar = document.getElementById('sani-master-svg');
      if (!avatar) return;

      const rect = avatar.getBoundingClientRect();
      const centerX = rect.left + rect.width / 2;
      const centerY = rect.top + rect.height / 2;

      const deltaX = e.clientX - centerX;
      const deltaY = e.clientY - centerY;

      // Limit pupil shift within socket bounds
      const shiftX = Math.max(-5, Math.min(5, deltaX / 60));
      const shiftY = Math.max(-4, Math.min(4, deltaY / 60));

      this.pupilLeft.style.transform = `translate(${shiftX}px, ${shiftY}px)`;
      this.pupilRight.style.transform = `translate(${shiftX}px, ${shiftY}px)`;
    });
  }

  startBlinkTimer() {
    const blink = () => {
      if (!this.pupilLeft || !this.pupilRight) return;
      this.isBlinking = true;
      this.pupilLeft.style.opacity = '0';
      this.pupilRight.style.opacity = '0';

      setTimeout(() => {
        this.pupilLeft.style.opacity = '1';
        this.pupilRight.style.opacity = '1';
        this.isBlinking = false;
      }, 150);

      // Random delay between 3s and 7s for natural blink
      const nextBlink = Math.random() * 4000 + 3000;
      setTimeout(blink, nextBlink);
    };

    setTimeout(blink, 3000);
  }

  bindToolEvents() {
    document.addEventListener('tool:start', () => {
      this.say("Processing your request...", "thinking", 3000);
    });

    document.addEventListener('tool:success', (e) => {
      const msg = e.detail?.message || "All done! Your file is ready.";
      this.say(msg, "success", 4000);
    });

    document.addEventListener('tool:error', (e) => {
      const msg = e.detail?.message || "Oops! Something went wrong. Let's try again.";
      this.say(msg, "error", 4000);
    });
  }
}

window.Sani = new SaniAssistant();
