<!-- SANI VIRTUAL ASSISTANT FLOATING WIDGET -->
<div id="sani-assistant-container" class="sani-floating">
  <div id="sani-speech-bubble">
    <div class="sani-speaker-badge">SANI AI</div>
    <span id="sani-speech-text">Welcome to Sanix Tool! How can I assist you today?</span>
  </div>

  <div class="sani-avatar-wrapper" onclick="if(window.Sani) window.Sani.say('Click any tool to start! Everything runs super fast.', 'happy');">
    <?php echo file_get_contents(__DIR__ . '/../assets/images/sani/sani-avatar.svg'); ?>
  </div>
</div>
