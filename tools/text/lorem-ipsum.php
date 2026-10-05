<?php
$pageTitle = "Lorem Ipsum Generator - Sanix Tool";
$pageDesc = "Generate placeholder dummy text by paragraphs, words or sentences for UI designs.";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
  <div class="tool-workspace">
    <div class="tool-header">
      <div class="tool-header-info">
        <div class="tool-icon">📄</div>
        <div>
          <h1 class="tool-header-title">Lorem Ipsum Generator</h1>
          <p class="tool-header-desc">Generate customizable placeholder dummy text for web design mockups.</p>
        </div>
      </div>
      <button class="fav-btn" data-tool="lorem-ipsum" title="Favorite this tool">🤍</button>
    </div>

    <div class="tool-controls-grid">
      <div class="form-group">
        <label class="form-label">Generate By</label>
        <select id="lorem-type" class="form-control">
          <option value="paragraphs">Paragraphs</option>
          <option value="words">Words</option>
          <option value="sentences">Sentences</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Quantity</label>
        <input type="number" id="lorem-count" class="form-control" value="3" min="1" max="100">
      </div>

      <div class="form-group" style="display:flex; align-items:flex-end;">
        <button class="btn btn-primary" id="generate-btn" style="width:100%;">⚡ Generate Text</button>
      </div>
    </div>

    <div class="form-group" style="margin-top:1.5rem;">
      <textarea id="lorem-output" class="form-control" style="min-height: 260px;" readonly></textarea>
    </div>

    <div class="tool-action-bar">
      <button class="btn btn-secondary" id="copy-btn">📋 Copy Placeholder Text</button>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  trackToolUsage('lorem-ipsum');

  const loremWords = [
    "lorem", "ipsum", "dolor", "sit", "amet", "consectetur", "adipiscing", "elit", "sed", "do",
    "eiusmod", "tempor", "incididunt", "ut", "labore", "et", "dolore", "magna", "aliqua", "ut",
    "enim", "ad", "minim", "veniam", "quis", "nostrud", "exercitation", "ullamco", "laboris",
    "nisi", "ut", "aliquip", "ex", "ea", "commodo", "consequat", "duis", "aute", "irure", "dolor",
    "in", "reprehenderit", "in", "voluptate", "velit", "esse", "cillum", "dolore", "eu", "fugiat",
    "nulla", "pariatur", "excepteur", "sint", "occaecat", "cupidatat", "non", "proident", "sunt",
    "in", "culpa", "qui", "officia", "deserunt", "mollit", "anim", "id", "est", "laborum"
  ];

  function getWord() {
    return loremWords[Math.floor(Math.random() * loremWords.length)];
  }

  function getSentence() {
    const len = Math.floor(Math.random() * 8) + 6;
    let sentence = [];
    for (let i = 0; i < len; i++) sentence.push(getWord());
    let res = sentence.join(' ');
    return res.charAt(0).toUpperCase() + res.slice(1) + '.';
  }

  function getParagraph() {
    const count = Math.floor(Math.random() * 3) + 4;
    let p = [];
    for (let i = 0; i < count; i++) p.push(getSentence());
    return p.join(' ');
  }

  function generate() {
    const type = document.getElementById('lorem-type').value;
    const count = parseInt(document.getElementById('lorem-count').value) || 3;
    let res = [];

    if (type === 'paragraphs') {
      for (let i = 0; i < count; i++) res.push(getParagraph());
      document.getElementById('lorem-output').value = res.join('\n\n');
    } else if (type === 'sentences') {
      for (let i = 0; i < count; i++) res.push(getSentence());
      document.getElementById('lorem-output').value = res.join(' ');
    } else {
      for (let i = 0; i < count; i++) res.push(getWord());
      document.getElementById('lorem-output').value = res.join(' ');
    }
  }

  document.getElementById('generate-btn').addEventListener('click', generate);
  document.getElementById('copy-btn').addEventListener('click', () => {
    copyToClipboard(document.getElementById('lorem-output').value, "Lorem ipsum text copied!");
  });

  generate();
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
