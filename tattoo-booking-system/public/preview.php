<?php
$humanImage = '/assets/images/men.jpg'; // Change filename if needed
// List of tattoo images (add your own PNG/SVGs here)
$tattoos = [
    '/assets/tattoos/tatu1.jpg',
    '/assets/tattoos/tatu2.jpg',
    '/assets/tattoos/tattoo3.png',
    // Add more as needed
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tattoo Preview on Your Body</title>
  <meta name="viewport" content="width=400, initial-scale=1">
  <style>
    body {
      min-height: 100vh;
      margin: 0;
      font-family: 'Segoe UI', Arial, sans-serif;
      color: #f3f3f3;
      /* Use the same background as your login page */
      background: linear-gradient(135deg, #1a1d23 0%, #232a36 100%);
      background-size: 200% 200%;
      animation: bgMove 8s ease-in-out infinite alternate;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    @keyframes bgMove {
      0% { background-position: 0% 50%; }
      100% { background-position: 100% 50%; }
    }
    .main-wrapper {
      display: flex;
      gap: 32px;
      margin-top: 40px;
      animation: fadeIn 1.2s;
    }
    .sidebar {
      background: rgba(34, 39, 51, 0.82);
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
      border-radius: 20px;
      padding: 24px 14px;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-width: 90px;
      max-width: 110px;
      gap: 18px;
      backdrop-filter: blur(8px);
      border: 1.5px solid rgba(255,255,255,0.08);
      animation: popIn 1.1s cubic-bezier(.6,-0.28,.74,.05);
      height: 520px;
      overflow-y: auto;
    }
    .sidebar-title {
      font-size: 1.1em;
      font-weight: 600;
      margin-bottom: 10px;
      color: #b0b6c3;
      letter-spacing: 1px;
      text-align: center;
    }
    .tattoo-thumb {
      width: 60px;
      height: 60px;
      background: #232a36;
      border-radius: 12px;
      box-shadow: 0 2px 8px #0003;
      margin-bottom: 6px;
      cursor: grab;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: box-shadow 0.2s, transform 0.2s;
      border: 1.5px solid transparent;
      user-select: none;
    }
    .tattoo-thumb img {
      max-width: 48px;
      max-height: 48px;
      pointer-events: none;
      user-select: none;
    }
    .tattoo-thumb:hover, .tattoo-thumb:focus {
      box-shadow: 0 4px 16px #0006;
      border: 1.5px solid #888;
      transform: scale(1.08);
      background: #2e3545;
      outline: none;
    }
    .preview-card {
      background: rgba(34, 39, 51, 0.82);
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.25);
      border-radius: 24px;
      padding: 24px 24px 18px 24px;
      display: flex;
      flex-direction: column;
      align-items: center;
      backdrop-filter: blur(8px);
      border: 1.5px solid rgba(255,255,255,0.08);
      animation: popIn 1.1s cubic-bezier(.6,-0.28,.74,.05);
    }
    h2 {
      margin-bottom: 18px;
      letter-spacing: 1px;
      font-weight: 700;
      font-size: 2em;
      text-align: center;
      color: #f3f3f3;
      text-shadow: 0 2px 8px #0006;
      animation: fadeInDown 1s;
    }
    @keyframes fadeInDown {
      from { opacity: 0; transform: translateY(-30px);}
      to { opacity: 1; transform: translateY(0);}
    }
    @keyframes popIn {
      0% { opacity: 0; transform: scale(0.92);}
      100% { opacity: 1; transform: scale(1);}
    }
    #container {
      position: relative;
      box-shadow: 0 8px 32px rgba(0,0,0,0.45), 0 1.5px 6px rgba(0,0,0,0.15);
      border-radius: 18px;
      overflow: hidden;
      background: rgba(35,42,54,0.98);
      margin-bottom: 18px;
      width: 320px;
      height: 480px;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: fadeIn 1.2s;
    }
    @keyframes fadeIn {
      from { opacity: 0;}
      to { opacity: 1;}
    }
    #human {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: contain;
      z-index: 1;
      border-radius: 12px;
      background: #222;
      user-select: none;
      touch-action: none;
      cursor: grab;
      transition: filter 0.2s, box-shadow 0.3s;
      box-shadow: 0 2px 12px #0004;
    }
    #human:active {
      cursor: grabbing;
      filter: brightness(0.96) blur(1px);
    }
    .tattoo-instance {
      position: absolute;
      width: 60px;
      height: 60px;
      cursor: grab;
      z-index: 2;
      opacity: 0.92;
      transition: filter 0.2s, transform 0.2s;
      filter: drop-shadow(0 2px 8px #0008);
      touch-action: none;
      user-select: none;
      animation: fadeIn 1.5s;
    }
    .tattoo-instance:active {
      cursor: grabbing;
      filter: drop-shadow(0 0 0 #0000) brightness(0.92);
      transform: scale(1.08) rotate(-2deg);
    }
    .note {
      color: #b0b6c3;
      font-size: 1.05em;
      margin-top: 10px;
      margin-bottom: 0;
      text-align: center;
      background: rgba(35,42,54,0.92);
      border-radius: 8px;
      padding: 10px 18px;
      box-shadow: 0 2px 8px #0002;
      max-width: 320px;
      animation: fadeIn 1.3s;
    }
    @media (max-width: 700px) {
      .main-wrapper { flex-direction: column; gap: 18px; }
      .sidebar { flex-direction: row; min-width: 0; max-width: 100vw; height: auto; padding: 12px 4vw; }
      .sidebar-title { margin-bottom: 0; }
    }
    @media (max-width: 400px) {
      #container { width: 98vw; height: 70vw; max-width: 340px; max-height: 540px; }
      .note { max-width: 98vw; }
      .preview-card { padding: 18px 4vw 12px 4vw; }
    }
    .tattoo-instance, .tattoo-thumb img {
      background: none !important;
      /* Optional: make white pixels transparent (works only for pure white) */
      mix-blend-mode: multiply;
    }
  </style>
</head>
<body>
  <div class="main-wrapper">
    <div class="sidebar">
      <div class="sidebar-title">Tattoos</div>
      <?php foreach ($tattoos as $src): ?>
        <div class="tattoo-thumb" draggable="true" tabindex="0" title="Drag to body">
          <img src="<?php echo $src; ?>" alt="Tattoo">
        </div>
      <?php endforeach; ?>
    </div>
    <div class="preview-card">
      <h2>Tattoo Preview on Your Body</h2>
      <div id="container">
        <img id="human" src="<?php echo $humanImage; ?>" alt="Human Body">
        <!-- Tattoos will be dropped here -->
      </div>
      <div class="note">
        Drag a tattoo from the left and drop it on the body.<br>
        You can move and place multiple tattoos.<br>
        Use mouse wheel or pinch to zoom the body.<br>
        Drag the body to move when zoomed in.
      </div>
    </div>
  </div>
  <script>
    // --- Drag & Drop Tattoos ---
    const container = document.getElementById('container');
    let draggingTattooSrc = null;

    // Make sidebar tattoos draggable
    document.querySelectorAll('.tattoo-thumb').forEach(thumb => {
      thumb.addEventListener('dragstart', function(e) {
        draggingTattooSrc = this.querySelector('img').src;
        e.dataTransfer.setData('text/plain', draggingTattooSrc);
      });
      // Keyboard accessibility: Enter to add tattoo to center
      thumb.addEventListener('keydown', function(e) {
        if (e.key === "Enter" || e.key === " ") {
          addTattooToBody(this.querySelector('img').src, container.clientWidth/2-30, container.clientHeight/2-30);
        }
      });
    });

    // Allow drop on container
    container.addEventListener('dragover', function(e) {
      e.preventDefault();
    });
    container.addEventListener('drop', function(e) {
      e.preventDefault();
      const rect = container.getBoundingClientRect();
      const x = e.clientX - rect.left - 30; // center of tattoo
      const y = e.clientY - rect.top - 30;
      addTattooToBody(draggingTattooSrc, x, y);
      draggingTattooSrc = null;
    });

    // Add tattoo image to body at position
    function addTattooToBody(src, x, y) {
      const img = document.createElement('img');
      img.src = src;
      img.className = 'tattoo-instance';
      img.style.left = x + 'px';
      img.style.top = y + 'px';
      makeTattooDraggable(img);
      container.appendChild(img);
    }

    // Make tattoo images draggable inside container
    function makeTattooDraggable(img) {
      let isDragging = false, offsetX = 0, offsetY = 0;
      let scale = 1, minScale = 0.3, maxScale = 3;

      img.addEventListener('mousedown', function(e) {
        isDragging = true;
        offsetX = e.offsetX;
        offsetY = e.offsetY;
        img.style.zIndex = 10;
        e.stopPropagation();
      });
      document.addEventListener('mousemove', function(e) {
        if (isDragging) {
          const rect = container.getBoundingClientRect();
          let x = e.clientX - rect.left - offsetX;
          let y = e.clientY - rect.top - offsetY;
          x = Math.max(0, Math.min(container.clientWidth - img.clientWidth * scale, x));
          y = Math.max(0, Math.min(container.clientHeight - img.clientHeight * scale, y));
          img.style.left = x + 'px';
          img.style.top = y + 'px';
        }
      });
      document.addEventListener('mouseup', function() {
        isDragging = false;
        img.style.zIndex = 2;
      });

      // Touch support for drag
      img.addEventListener('touchstart', function(e) {
        isDragging = true;
        const touch = e.touches[0];
        const rect = img.getBoundingClientRect();
        offsetX = touch.clientX - rect.left;
        offsetY = touch.clientY - rect.top;
        img.style.zIndex = 10;
        e.stopPropagation();
        e.preventDefault();
      });
      document.addEventListener('touchmove', function(e) {
        if (isDragging) {
          const touch = e.touches[0];
          const rect = container.getBoundingClientRect();
          let x = touch.clientX - rect.left - offsetX;
          let y = touch.clientY - rect.top - offsetY;
          x = Math.max(0, Math.min(container.clientWidth - img.clientWidth * scale, x));
          y = Math.max(0, Math.min(container.clientHeight - img.clientHeight * scale, y));
          img.style.left = x + 'px';
          img.style.top = y + 'px';
          e.preventDefault();
        }
      });
      document.addEventListener('touchend', function() {
        isDragging = false;
        img.style.zIndex = 2;
      });

      // Wheel zoom for tattoo
      img.addEventListener('wheel', function(e) {
        e.preventDefault();
        let oldScale = scale;
        if (e.deltaY < 0) {
          scale = Math.min(maxScale, scale + 0.1);
        } else {
          scale = Math.max(minScale, scale - 0.1);
        }
        img.style.transform = `scale(${scale})`;
        // Optional: keep the center of the tattoo fixed while scaling
        // (advanced: can be added if needed)
      });

      // Pinch zoom for touch devices
      let lastDist = null;
      img.addEventListener('touchmove', function(e) {
        if (e.touches.length === 2) {
          e.preventDefault();
          const dx = e.touches[0].clientX - e.touches[1].clientX;
          const dy = e.touches[0].clientY - e.touches[1].clientY;
          const dist = Math.sqrt(dx * dx + dy * dy);
          if (lastDist) {
            let delta = (dist - lastDist) / 200;
            scale = Math.max(minScale, Math.min(maxScale, scale + delta));
            img.style.transform = `scale(${scale})`;
          }
          lastDist = dist;
        }
      });
      img.addEventListener('touchend', function(e) {
        lastDist = null;
      });
    }

    // --- Human Zoom & Pan ---
    const human = document.getElementById('human');
    let scale = 1;
    let minScale = 1;
    let maxScale = 3;
    let humanX = 0, humanY = 0;
    let isDraggingHuman = false, dragStartX = 0, dragStartY = 0, startHumanX = 0, startHumanY = 0;

    function updateHumanTransform() {
      human.style.transform = `translate(${humanX}px, ${humanY}px) scale(${scale})`;
    }

    // Mouse wheel zoom
    container.addEventListener('wheel', function(e) {
      e.preventDefault();
      const oldScale = scale;
      if (e.deltaY < 0) {
        scale = Math.min(maxScale, scale + 0.1);
      } else {
        scale = Math.max(minScale, scale - 0.1);
      }
      // Adjust pan so zoom is centered on mouse
      const rect = container.getBoundingClientRect();
      const mx = e.clientX - rect.left - container.clientWidth / 2;
      const my = e.clientY - rect.top - container.clientHeight / 2;
      humanX -= mx * (scale - oldScale) / scale;
      humanY -= my * (scale - oldScale) / scale;
      updateHumanTransform();
    });

    // Drag to pan
    human.addEventListener('mousedown', function(e) {
      if (scale === 1) return; // No pan if not zoomed
      isDraggingHuman = true;
      dragStartX = e.clientX;
      dragStartY = e.clientY;
      startHumanX = humanX;
      startHumanY = humanY;
      e.preventDefault();
    });
    document.addEventListener('mousemove', function(e) {
      if (isDraggingHuman) {
        humanX = startHumanX + (e.clientX - dragStartX);
        humanY = startHumanY + (e.clientY - dragStartY);
        updateHumanTransform();
      }
    });
    document.addEventListener('mouseup', function() {
      isDraggingHuman = false;
    });

    // Touch pan
    human.addEventListener('touchstart', function(e) {
      if (scale === 1) return;
      isDraggingHuman = true;
      const touch = e.touches[0];
      dragStartX = touch.clientX;
      dragStartY = touch.clientY;
      startHumanX = humanX;
      startHumanY = humanY;
      e.preventDefault();
    });
    document.addEventListener('touchmove', function(e) {
      if (isDraggingHuman) {
        const touch = e.touches[0];
        humanX = startHumanX + (touch.clientX - dragStartX);
        humanY = startHumanY + (touch.clientY - dragStartY);
        updateHumanTransform();
        e.preventDefault();
      }
    });
    document.addEventListener('touchend', function() {
      isDraggingHuman = false;
    });

    // Initialize
    updateHumanTransform();
  </script>
</body>
</html>