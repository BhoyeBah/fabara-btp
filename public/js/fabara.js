/* FABARA Bâti – Finition – TP : interactions du site (sans dépendance). */
(function () {
  "use strict";

  function $(s, r) { return (r || document).querySelector(s); }
  function $$(s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); }
  function h(tag, attrs) {
    var el = document.createElement(tag); attrs = attrs || {};
    Object.keys(attrs).forEach(function (k) {
      var v = attrs[k]; if (v == null || v === false) return;
      if (k === "class") el.className = v;
      else if (k === "text") el.textContent = v;
      else if (k.slice(0, 2) === "on") el.addEventListener(k.slice(2), v);
      else el.setAttribute(k, v === true ? "" : v);
    });
    for (var i = 2; i < arguments.length; i++) { var c = arguments[i]; if (c != null && c !== false) el.append(c); }
    return el;
  }
  function fmt(n, d) { return Number(n).toLocaleString("fr-FR", { maximumFractionDigits: d == null ? 1 : d }); }
  function num(v) { var n = parseFloat(String(v || "").replace(",", ".")); return isFinite(n) && n > 0 ? n : 0; }

  /* Menu mobile */
  var mb = $("#menuBtn"), mn = $("#mnav");
  if (mb && mn) {
    mb.addEventListener("click", function () {
      var open = mn.classList.toggle("open");
      mb.setAttribute("aria-expanded", String(open));
    });
    $$("a", mn).forEach(function (a) { a.addEventListener("click", function () { mn.classList.remove("open"); mb.setAttribute("aria-expanded", "false"); }); });
  }

  /* Liens WhatsApp : data-wa="texte" → https://wa.me/<numéro>?text=… */
  var WA = document.body.getAttribute("data-whatsapp") || "";
  $$("[data-wa]").forEach(function (a) {
    a.href = "https://wa.me/" + WA + "?text=" + encodeURIComponent(a.getAttribute("data-wa"));
  });

  /* Bouton « Copier le numéro » */
  $$("[data-copy]").forEach(function (b) {
    b.addEventListener("click", function () {
      var txt = b.getAttribute("data-copy");
      var done = function () { b.textContent = "Numéro copié"; };
      if (navigator.clipboard) navigator.clipboard.writeText(txt).then(done, function () {});
    });
  });

  /* Confirmation avant suppression : <form data-confirm="Texte"> */
  $$("form[data-confirm]").forEach(function (f) {
    f.addEventListener("submit", function (e) {
      if (!window.confirm(f.getAttribute("data-confirm"))) e.preventDefault();
    });
  });

  /* Galerie : filtres par catégorie */
  var gal = $("#gallery"), filters = $("#galFilters");
  if (gal && filters) {
    $$("button", filters).forEach(function (b) {
      b.addEventListener("click", function () {
        var cat = b.getAttribute("data-cat");
        $$("button", filters).forEach(function (x) { x.setAttribute("aria-pressed", String(x === b)); });
        $$("figure", gal).forEach(function (f) { f.hidden = cat !== "Tout" && f.getAttribute("data-cat") !== cat; });
      });
    });
  }

  /* Surfaces : accepter la virgule (12,5) avant l'envoi des formulaires */
  $$("form").forEach(function (f) {
    f.addEventListener("submit", function () {
      $$("input[data-decimal]", f).forEach(function (i) { i.value = i.value.replace(",", ".").trim(); });
    });
  });

  /* Calculateur de quantités */
  var calcBox = $("#calcFields"), out = $("#calcOut");
  if (calcBox && out) {
    var calcT = "peinture", vals = {}, last = null;
    var CF = {
      peinture: [["L", "Longueur pièce", "m", 5], ["l", "Largeur pièce", "m", 4], ["H", "Hauteur sous plafond", "m", 2.8], ["o", "Portes et fenêtres", "m²", 4], ["c", "Nombre de couches", "", 2]],
      carrelage: [["L", "Longueur", "m", 5], ["l", "Largeur", "m", 4], ["fmt", "Format carreau", "sel", "60x60"]],
      plafond: [["L", "Longueur", "m", 5], ["l", "Largeur", "m", 4]],
      facade: [["P", "Longueur totale des façades", "m", 40], ["H", "Hauteur", "m", 6.5], ["o", "Ouvertures à déduire", "m²", 18], ["c", "Nombre de couches", "", 2]]
    };
    var FMTS = {
      "60x60": { m2: 1.44, txt: "60×60 cm · carton 4 carreaux = 1,44 m²" },
      "40x40": { m2: 1.6, txt: "40×40 cm · carton 10 carreaux = 1,60 m²" },
      "120x60": { m2: 1.44, txt: "120×60 cm · carton 2 carreaux = 1,44 m²" },
      "30x30": { m2: 1.35, txt: "30×30 cm · carton 15 carreaux = 1,35 m²" }
    };
    var g = function (k) {
      var f = CF[calcT].filter(function (x) { return x[0] === k; })[0];
      var el = document.getElementById("c-" + calcT + "-" + k);
      if (f[2] === "sel") return el ? el.value : f[3];
      return el ? num(el.value) : f[3];
    };
    var row = function (lbl, val, big) { return h("div", { class: "out-row" + (big ? " big" : "") }, h("span", { text: lbl }), h("b", { text: val })); };

    var compute = function () {
      out.textContent = ""; var rows = [], sum = "", surf = 0, label = "";
      if (calcT === "peinture") {
        var L = g("L"), l = g("l"), H = g("H"), o = g("o"), c = Math.max(1, Math.round(g("c")) || 1);
        surf = Math.max(0, 2 * (L + l) * H - o); var lit = surf * c / 10;
        rows = [row("Surface des murs", fmt(surf) + " m²", true), row("Peinture (" + c + " couches, 10 m²/L)", fmt(Math.ceil(lit), 0) + " L"), row("Seaux de 20 L", fmt(Math.ceil(lit / 20), 0)), row("Enduit de lissage (≈1,2 kg/m²)", fmt(Math.ceil(surf * 1.2 / 25), 0) + " sacs 25 kg")];
        label = "Peinture"; sum = "Peinture murs : " + fmt(surf) + " m², " + Math.ceil(lit) + " L de peinture (" + c + " couches)";
      }
      if (calcT === "carrelage") {
        var L2 = g("L"), l2 = g("l"), f = FMTS[g("fmt")] || FMTS["60x60"];
        surf = L2 * l2; var withLoss = surf * 1.1, cartons = Math.ceil(withLoss / f.m2);
        rows = [row("Surface au sol", fmt(surf) + " m²", true), row("Avec 10 % de chutes", fmt(withLoss) + " m²"), row("Cartons à commander", fmt(cartons, 0)), row("Colle (≈5 kg/m²)", fmt(Math.ceil(surf * 5 / 25), 0) + " sacs 25 kg"), row("Plinthes (périmètre)", fmt(2 * (L2 + l2)) + " m")];
        label = "Carrelage"; sum = "Carrelage sol : " + fmt(surf) + " m², " + cartons + " cartons " + g("fmt");
      }
      if (calcT === "plafond") {
        var L3 = g("L"), l3 = g("l"); surf = L3 * l3;
        var plaques = Math.ceil(surf * 1.1 / 2.88), per = 2 * (L3 + l3), spots = Math.max(1, Math.round(surf / 2.5));
        rows = [row("Surface de plafond", fmt(surf) + " m²", true), row("Plaques BA13 1,2×2,4 m (+10 %)", fmt(plaques, 0)), row("Corniche / bande LED", fmt(per) + " m"), row("Spots encastrés (1 pour 2,5 m²)", fmt(spots, 0))];
        label = "Plafonds et décoration"; sum = "Faux plafond : " + fmt(surf) + " m², " + plaques + " plaques BA13, " + fmt(per) + " m de corniche";
      }
      if (calcT === "facade") {
        var P = g("P"), H4 = g("H"), o4 = g("o"), c4 = Math.max(1, Math.round(g("c")) || 1);
        surf = Math.max(0, P * H4 - o4); var lit4 = surf * c4 / 8;
        rows = [row("Surface de façade", fmt(surf) + " m²", true), row("Peinture façade (" + c4 + " couches, 8 m²/L)", fmt(Math.ceil(lit4), 0) + " L"), row("Seaux de 20 L", fmt(Math.ceil(lit4 / 20), 0)), row("Échafaudage", H4 > 3 ? "À prévoir" : "Escabeau suffisant")];
        label = "Façades"; sum = "Façade : " + fmt(surf) + " m², " + Math.ceil(lit4) + " L de peinture (" + c4 + " couches)";
      }
      last = { label: label, surf: Math.round(surf * 10) / 10, txt: sum };
      out.append(h("span", { class: "eyebrow", text: "Estimation des quantités" }));
      rows.forEach(function (r) { out.append(r); });
      out.append(h("p", { class: "note", text: "Estimation indicative. Les quantités exactes et le prix sont fixés dans le devis après la visite sur site." }));
      if ($("#devisForm")) out.append(h("button", { type: "button", class: "btn btn-gold", style: "justify-self:start", text: "Joindre à ma demande de devis", onclick: attach }));
    };

    var renderFields = function () {
      calcBox.textContent = "";
      CF[calcT].forEach(function (f) {
        var id = "c-" + calcT + "-" + f[0], v = vals[id] != null ? vals[id] : f[3], ctl;
        if (f[2] === "sel") {
          ctl = h("select", { id: id }); Object.keys(FMTS).forEach(function (k) { ctl.append(h("option", { value: k, selected: k === v, text: FMTS[k].txt })); });
        } else {
          ctl = h("div", { class: "unit" }, h("input", { id: id, inputmode: "decimal", value: String(v).replace(".", ",") }), f[2] ? h("span", { text: f[2] }) : null);
        }
        calcBox.append(h("label", { class: "f", for: id, style: f[2] === "sel" ? "grid-column:1/-1" : null }, f[1], ctl));
        var inp = document.getElementById(id);
        inp.addEventListener("input", function () { vals[id] = inp.value; compute(); });
      });
      compute();
    };

    var attach = function () {
      var form = $("#devisForm"); if (!form || !last) return;
      $$("input[name='works[]']", form).forEach(function (i) { if (i.value === last.label) i.checked = true; });
      var s = $("[name='surface']", form); if (s && !s.value && last.surf) s.value = String(last.surf);
      var m = $("[name='message']", form);
      if (m && m.value.indexOf(last.txt) === -1) m.value = (m.value ? m.value + "\n" : "") + "Estimation du calculateur : " + last.txt;
      document.getElementById("devis").scrollIntoView({ behavior: "smooth" });
    };

    $$("#calcType button").forEach(function (b) {
      b.addEventListener("click", function () {
        calcT = b.getAttribute("data-t");
        $$("#calcType button").forEach(function (x) { x.setAttribute("aria-pressed", String(x === b)); });
        renderFields();
      });
    });
    renderFields();
  }

  /* Comparateurs avant / après */
  $$("[data-ba]").forEach(function (box) {
    var range = $(".ba-range", box);
    var set = function (v) { box.style.setProperty("--pos", v + "%"); };
    range.addEventListener("input", function () { set(range.value); });
    set(range.value);
  });

  /* Aperçu des photos choisies */
  $$("input[type=file][data-preview]").forEach(function (inp) {
    var box = document.getElementById(inp.getAttribute("data-preview"));
    inp.addEventListener("change", function () {
      if (!box) return; box.textContent = "";
      Array.prototype.slice.call(inp.files || [], 0, 5).forEach(function (file) {
        var img = h("img", { alt: file.name }); img.src = URL.createObjectURL(file);
        box.append(h("a", { href: "#", onclick: function (e) { e.preventDefault(); } }, img));
      });
    });
  });
})();
