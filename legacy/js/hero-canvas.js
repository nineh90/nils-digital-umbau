(function () {
  const canvas = document.getElementById("hero-canvas");
  if (!canvas) return;

  const ctx = canvas.getContext("2d");
  const ACCENT = { r: 0, g: 188, b: 212 };
  const MAX_DIST = 160;
  let nodes = [];
  let animId;

  function nodeCount() {
    return window.innerWidth < 600 ? 35 : 65;
  }

  function resize() {
    canvas.width  = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;
  }

  function createNodes() {
    nodes = [];
    const n = nodeCount();
    for (let i = 0; i < n; i++) {
      nodes.push({
        x:  Math.random() * canvas.width,
        y:  Math.random() * canvas.height,
        vx: (Math.random() - 0.5) * 0.45,
        vy: (Math.random() - 0.5) * 0.45,
        r:  Math.random() * 1.6 + 0.6,
      });
    }
  }

  function draw() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Verbindungslinien
    for (let i = 0; i < nodes.length; i++) {
      for (let j = i + 1; j < nodes.length; j++) {
        const dx   = nodes[i].x - nodes[j].x;
        const dy   = nodes[i].y - nodes[j].y;
        const dist = Math.sqrt(dx * dx + dy * dy);
        if (dist >= MAX_DIST) continue;

        const alpha = (1 - dist / MAX_DIST) * 0.28;
        ctx.beginPath();
        ctx.strokeStyle = `rgba(${ACCENT.r},${ACCENT.g},${ACCENT.b},${alpha})`;
        ctx.lineWidth   = 0.9;
        ctx.moveTo(nodes[i].x, nodes[i].y);
        ctx.lineTo(nodes[j].x, nodes[j].y);
        ctx.stroke();
      }
    }

    // Knoten
    nodes.forEach(node => {
      // Äußerer Glow
      const glow = ctx.createRadialGradient(node.x, node.y, 0, node.x, node.y, node.r * 5);
      glow.addColorStop(0,   `rgba(${ACCENT.r},${ACCENT.g},${ACCENT.b},0.25)`);
      glow.addColorStop(1,   `rgba(${ACCENT.r},${ACCENT.g},${ACCENT.b},0)`);
      ctx.beginPath();
      ctx.arc(node.x, node.y, node.r * 5, 0, Math.PI * 2);
      ctx.fillStyle = glow;
      ctx.fill();

      // Kern
      ctx.beginPath();
      ctx.arc(node.x, node.y, node.r, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(${ACCENT.r},${ACCENT.g},${ACCENT.b},0.75)`;
      ctx.fill();
    });
  }

  function update() {
    nodes.forEach(n => {
      n.x += n.vx;
      n.y += n.vy;
      if (n.x < 0 || n.x > canvas.width)  n.vx *= -1;
      if (n.y < 0 || n.y > canvas.height) n.vy *= -1;
    });
  }

  function loop() {
    update();
    draw();
    animId = requestAnimationFrame(loop);
  }

  // Tab sichtbar → Animation läuft, Tab versteckt → pausieren
  document.addEventListener("visibilitychange", () => {
    if (document.hidden) {
      cancelAnimationFrame(animId);
    } else {
      loop();
    }
  });

  window.addEventListener("resize", () => {
    resize();
    createNodes();
  });

  resize();
  createNodes();
  loop();
})();
