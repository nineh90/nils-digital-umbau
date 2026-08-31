(function () {

  let initialized = false;
  let stopEffectFn = null;

  function mmdd(d) {
    return String(d.getMonth() + 1).padStart(2, "0") + "-" + String(d.getDate()).padStart(2, "0");
  }

  function inRange(current, start, end) {
    return (start <= end)
      ? (current >= start && current <= end)
      : (current >= start || current <= end);
  }

  async function initSeasonal() {
    if (initialized) return;
    initialized = true;

    const seasonFile = "/assets/data/seasonal.json";

    let data = [];
    try {
      const res = await fetch(seasonFile, { cache: "no-cache" });
      data = await res.json();
    } catch (err) {
      console.warn("Seasonal JSON Fehler:", err);
      return;
    }

    const todayStr = mmdd(new Date());
    const matches = data.filter(e => inRange(todayStr, e.start, e.end));

    if (!matches.length) return;

    matches.sort((a, b) => (b.priority ?? 0) - (a.priority ?? 0));
    const active = matches[0];

    showSeasonPopup(active.message, active.effect);
  }


  // -------------------------
  // POPUP + ANIMATION
  // -------------------------
  function showSeasonPopup(message, effect) {
    const popup = document.createElement("div");
    popup.className = "season-center-popup";
    popup.innerHTML = `
      <div class="season-center-inner">
        <span class="season-close">&times;</span>
        <p>${message}</p>
      </div>
    `;

    document.body.appendChild(popup);
    requestAnimationFrame(() => popup.classList.add("visible"));

    // Starte Animation
    stopEffectFn = startEffect(effect);

    // Auto-close nach 5 Sekunden
    const autoClose = setTimeout(() => closePopup(popup), 5000);

    // Beim Klick auf X schließen
    popup.querySelector(".season-close").onclick = () => {
      clearTimeout(autoClose);
      closePopup(popup);
    };
  }

  function closePopup(popup) {
    if (!popup) return;
    if (stopEffectFn) stopEffectFn(); // Animation stoppen
    popup.classList.remove("visible");
    setTimeout(() => popup.remove(), 300);
  }


  // -------------------------
  // ANIMATIONEN
  // -------------------------

  function startEffect(type) {
    switch (type) {
      case "snow": return startSnow();
      case "hearts": return startHearts();
      case "leaves": return startLeaves();
      case "flowers": return startFlowers();
      case "pumpkin": return startPumpkins();
      case "confetti": return startConfetti();
      case "eggs": return startEaster();
      default: return () => {};
    }
  }


  // ❄️ Schneefall – leicht & schön
  function startSnow() {
    const { c, ctx } = createCanvas(1600);
    const flakes = Array.from({ length: 60 }, () => ({
      x: Math.random() * c.width,
      y: Math.random() * c.height,
      r: Math.random() * 2 + 1,
      s: Math.random() * 1 + 0.5
    }));

    let frameId;

    const draw = () => {
      ctx.clearRect(0, 0, c.width, c.height);
      ctx.fillStyle = "rgba(255,255,255,.9)";
      ctx.beginPath();
      for (const f of flakes) {
        ctx.moveTo(f.x, f.y);
        ctx.arc(f.x, f.y, f.r, 0, Math.PI * 2);
        f.y += f.s;
        if (f.y > c.height) {
          f.y = -5;
          f.x = Math.random() * c.width;
        }
      }
      ctx.fill();
      frameId = requestAnimationFrame(draw);
    };

    draw();

    return () => {
      cancelAnimationFrame(frameId);
      c.remove();
    };
  }


  // ❤️ Herzen
  function startHearts() {
    let elements = [];

    for (let i = 0; i < 12; i++) {
      const el = document.createElement("div");
      el.className = "float-heart";
      el.textContent = "❤️";
      el.style.left = Math.random() * 100 + "vw";
      el.style.animationDelay = (Math.random() * 3) + "s";
      document.body.appendChild(el);
      elements.push(el);
    }

    return () => elements.forEach(e => e.remove());
  }


  // 🍂 Blätter
  function startLeaves() {
    let elements = [];

    for (let i = 0; i < 12; i++) {
      const el = document.createElement("div");
      el.className = "float-leaf";
      el.textContent = Math.random() > 0.5 ? "🍁" : "🍂";
      el.style.left = Math.random() * 100 + "vw";
      el.style.animationDelay = (Math.random() * 4) + "s";
      document.body.appendChild(el);
      elements.push(el);
    }

    return () => elements.forEach(e => e.remove());
  }


  // 🌸 Blumen
  function startFlowers() {
    let elements = [];

    for (let i = 0; i < 12; i++) {
      const el = document.createElement("div");
      el.className = "float-flower";
      el.textContent = "🌸";
      el.style.left = Math.random() * 100 + "vw";
      document.body.appendChild(el);
      elements.push(el);
    }

    return () => elements.forEach(e => e.remove());
  }


  // 🎃 Kürbisse
  function startPumpkins() {
    let elements = [];

    for (let i = 0; i < 10; i++) {
      const el = document.createElement("div");
      el.className = "float-pumpkin";
      el.textContent = "🎃";
      el.style.left = Math.random() * 100 + "vw";
      document.body.appendChild(el);
      elements.push(el);
    }

    return () => elements.forEach(e => e.remove());
  }


  // 🎉 Konfetti
function startConfetti() {
  const { c, ctx } = createCanvas(2000);

  const centerX = window.innerWidth / 2;
  const startY = window.innerHeight - 40;

  const particles = [];
  const maxParticles = 260; // Gesamtmenge
  let spawnIntervalId = null;
  let frameId = null;

  // Funktion erzeugt laufend neue Funken
  function spawnParticles() {
    for (let i = 0; i < 10; i++) { // 10 neue Funken alle 60ms
      particles.push({
        x: centerX,
        y: startY,
        angle: (Math.random() * (Math.PI / 2)) + (Math.PI / 4) * -1,

        speed: Math.random() * 4 + 5,
        size: Math.random() * 4 + 2,
        gravity: 0.15,
        opacity: 1,
        color: `hsl(${Math.random() * 360}, 90%, 60%)`
      });

      if (particles.length > maxParticles) {
        particles.shift(); // alte Partikel entfernen
      }
    }
  }

  // Zeichnen
  function draw() {
    ctx.clearRect(0, 0, c.width, c.height);

    particles.forEach(p => {
      ctx.fillStyle = p.color;
      ctx.globalAlpha = p.opacity;

      ctx.fillRect(p.x, p.y, p.size, p.size);

      p.x += Math.cos(p.angle) * p.speed;
      p.y += Math.sin(p.angle) * p.speed;

      // Verlangsamen + schwerer werden
      p.speed *= 0.95;
      p.gravity += 0.04;
      p.y += p.gravity;

      // langsam ausfaden
      p.opacity -= 0.015;
    });

    frameId = requestAnimationFrame(draw);
  }

  // Start: Sprudeln lassen
  spawnIntervalId = setInterval(spawnParticles, 70); // alle 70ms neue Funken
  draw();

  // Rückgabe: Stop Funktion
  return () => {
    clearInterval(spawnIntervalId);
    cancelAnimationFrame(frameId);
    c.remove();
  };
}


  // Fullscreen Canvas
  function createCanvas(z = 1500) {
    const c = document.createElement("canvas");
    c.style.position = "fixed";
    c.style.inset = "0";
    c.style.zIndex = z;
    c.style.pointerEvents = "none";
    document.body.appendChild(c);

    const ctx = c.getContext("2d");

    const resize = () => {
      c.width = window.innerWidth;
      c.height = window.innerHeight;
    };
    resize();
    window.addEventListener("resize", resize);

    return { c, ctx };
  }


  // 🥚 Ostern
  function startEaster() {
    let elements = [];
    const eggEmojis = ["🥚","🐣","🐥","🌼"];

    for (let i = 0; i < 14; i++) {
      const el = document.createElement("div");
      el.className = "float-egg";
      el.textContent = eggEmojis[Math.floor(Math.random() * eggEmojis.length)];
      el.style.left = Math.random() * 100 + "vw";
      el.style.fontSize = (Math.random() * 1.2 + 1.6) + "rem";
      el.style.animationDelay = (Math.random() * 5) + "s";
      document.body.appendChild(el);
      elements.push(el);
    }

    return () => elements.forEach(e => e.remove());
  }

  // Start
  document.addEventListener("DOMContentLoaded", initSeasonal);

})();
