<?php
$pageTitle = "Contact Us - Sanix Tool";
$pageDesc = "Get in touch with the Sanix Tool development team for feedback, support or feature requests.";
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding-top: 3rem; padding-bottom: 5rem; display:flex; justify-content:center;">
  <div class="card" style="width:100%; max-width:540px; padding:2.5rem;">
    <h2 style="margin-bottom:0.5rem;">Contact & Feedback</h2>
    <p style="color:var(--text-muted); font-size:0.92rem; margin-bottom:1.5rem;">Have questions, feedback, or a feature request? Send us a message below.</p>

    <form id="contact-form">
      <?= csrfField() ?>

      <div class="form-group">
        <label class="form-label">Your Name *</label>
        <input type="text" name="name" class="form-control" required placeholder="John Doe">
      </div>

      <div class="form-group">
        <label class="form-label">Your Email *</label>
        <input type="email" name="email" class="form-control" required placeholder="john@example.com">
      </div>

      <div class="form-group">
        <label class="form-label">Message *</label>
        <textarea name="message" class="form-control" required style="min-height:140px;" placeholder="Tell us how we can help..."></textarea>
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%; margin-top:1rem; padding:0.8rem;">
        ✉️ Send Message
      </button>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('contact-form');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    try {
      const response = await fetch('/api/contact.php', {
        method: 'POST',
        body: formData
      });
      const data = await response.json();

      if (data.success) {
        showToast(data.message, "success");
        if (window.Sani) window.Sani.say("Thank you for your message!", "happy");
        form.reset();
      } else {
        showToast(data.message || "Error submitting form", "error");
      }
    } catch (err) {
      showToast("Network connection error", "error");
    }
  });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
