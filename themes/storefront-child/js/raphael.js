// â”‚ Copyright Â© 2008-2012 Dmitry Baranovskiy (http://raphaeljs.com)    â”‚ \\
// â”‚ Copyright Â© 2008-2012 Sencha Labs (http://sencha.com)              â”‚ \\
! function (t, e) {
    "function" == typeof define && define.amd ? define("eve", function () {
        return e()
    }) : "object" == typeof exports ? module.exports = e() : t.eve = e()
}(this, function () {
    var t, e, r = "0.4.2",
        i = "hasOwnProperty",
        n = /[\.\/]/,
        a = "*",
        s = function () {},
        o = function (t, e) {
            return t - e
        },
        l = {
            "n": {}
        },
        h = function (r, i) {
            r = String(r);
            var n, a = e,
                s = Array.prototype.slice.call(arguments, 2),
                l = h.listeners(r),
                u = 0,
                c = [],
                f = {},
                p = [],
                d = t;
            t = r, e = 0;
            for (var g = 0, x = l.length; x > g; g++) "zIndex" in l[g] && (c.push(l[g].zIndex), l[g].zIndex < 0 && (f[l[g].zIndex] = l[g]));
            for (c.sort(o); c[u] < 0;)
                if (n = f[c[u++]], p.push(n.apply(i, s)), e) return e = a, p;
            for (g = 0; x > g; g++)
                if (n = l[g], "zIndex" in n)
                    if (n.zIndex == c[u]) {
                        if (p.push(n.apply(i, s)), e) break;
                        do
                            if (u++, n = f[c[u]], n && p.push(n.apply(i, s)), e) break; while (n)
                    } else f[n.zIndex] = n;
            else if (p.push(n.apply(i, s)), e) break;
            return e = a, t = d, p.length ? p : null
        };
    return h._events = l, h.listeners = function (t) {
        var e, r, i, s, o, h, u, c, f = t.split(n),
            p = l,
            d = [p],
            g = [];
        for (s = 0, o = f.length; o > s; s++) {
            for (c = [], h = 0, u = d.length; u > h; h++)
                for (p = d[h].n, r = [p[f[s]], p[a]], i = 2; i--;) e = r[i], e && (c.push(e), g = g.concat(e.f || []));
            d = c
        }
        return g
    }, h.on = function (t, e) {
        if (t = String(t), "function" != typeof e) return function () {};
        for (var r = t.split(n), i = l, a = 0, o = r.length; o > a; a++) i = i.n, i = i.hasOwnProperty(r[a]) && i[r[a]] || (i[r[a]] = {
            "n": {}
        });
        for (i.f = i.f || [], a = 0, o = i.f.length; o > a; a++)
            if (i.f[a] == e) return s;
        return i.f.push(e),
            function (t) {
                +t == +t && (e.zIndex = +t)
            }
    }, h.f = function (t) {
        var e = [].slice.call(arguments, 1);
        return function () {
            h.apply(null, [t, null].concat(e).concat([].slice.call(arguments, 0)))
        }
    }, h.stop = function () {
        e = 1
    }, h.nt = function (e) {
        return e ? new RegExp("(?:\\.|\\/|^)" + e + "(?:\\.|\\/|$)").test(t) : t
    }, h.nts = function () {
        return t.split(n)
    }, h.off = h.unbind = function (t, e) {
        if (!t) return void(h._events = l = {
            "n": {}
        });
        var r, s, o, u, c, f, p, d = t.split(n),
            g = [l];
        for (u = 0, c = d.length; c > u; u++)
            for (f = 0; f < g.length; f += o.length - 2) {
                if (o = [f, 1], r = g[f].n, d[u] != a) r[d[u]] && o.push(r[d[u]]);
                else
                    for (s in r) r[i](s) && o.push(r[s]);
                g.splice.apply(g, o)
            }
        for (u = 0, c = g.length; c > u; u++)
            for (r = g[u]; r.n;) {
                if (e) {
                    if (r.f) {
                        for (f = 0, p = r.f.length; p > f; f++)
                            if (r.f[f] == e) {
                                r.f.splice(f, 1);
                                break
                            }!r.f.length && delete r.f
                    }
                    for (s in r.n)
                        if (r.n[i](s) && r.n[s].f) {
                            var x = r.n[s].f;
                            for (f = 0, p = x.length; p > f; f++)
                                if (x[f] == e) {
                                    x.splice(f, 1);
                                    break
                                }!x.length && delete r.n[s].f
                        }
                } else {
                    delete r.f;
                    for (s in r.n) r.n[i](s) && r.n[s].f && delete r.n[s].f
                }
                r = r.n
            }
    }, h.once = function (t, e) {
        var r = function () {
            return h.unbind(t, r), e.apply(this, arguments)
        };
        return h.on(t, r)
    }, h.version = r, h.toString = function () {
        return "You are running Eve " + r
    }, h
}),
function (t, e) {
    "function" == typeof define && define.amd ? define("raphael.core", ["eve"], function (t) {
        return e(t)
    }) : "object" == typeof exports ? module.exports = e(require("eve")) : t.Raphael = e(t.eve)
}(this, function (t) {
    function e(r) {
        if (e.is(r, "function")) return m ? r() : t.on("raphael.DOMload", r);
        if (e.is(r, H)) return e._engine.create[A](e, r.splice(0, 3 + e.is(r[0], W))).add(r);
        var i = Array.prototype.slice.call(arguments, 0);
        if (e.is(i[i.length - 1], "function")) {
            var n = i.pop();
            return m ? n.call(e._engine.create[A](e, i)) : t.on("raphael.DOMload", function () {
                n.call(e._engine.create[A](e, i))
            })
        }
        return e._engine.create[A](e, arguments)
    }

    function r(t) {
        if ("function" == typeof t || Object(t) !== t) return t;
        var e = new t.constructor;
        for (var i in t) t[B](i) && (e[i] = r(t[i]));
        return e
    }

    function i(t, e) {
        for (var r = 0, i = t.length; i > r; r++)
            if (t[r] === e) return t.push(t.splice(r, 1)[0])
    }

    function n(t, e, r) {
        function n() {
            var a = Array.prototype.slice.call(arguments, 0),
                s = a.join("\u2400"),
                o = n.cache = n.cache || {},
                l = n.count = n.count || [];
            return o[B](s) ? (i(l, s), r ? r(o[s]) : o[s]) : (l.length >= 1e3 && delete o[l.shift()], l.push(s), o[s] = t[A](e, a), r ? r(o[s]) : o[s])
        }
        return n
    }

    function a() {
        return this.hex
    }

    function s(t, e) {
        for (var r = [], i = 0, n = t.length; n - 2 * !e > i; i += 2) {
            var a = [{
                "x": +t[i - 2],
                "y": +t[i - 1]
            }, {
                "x": +t[i],
                "y": +t[i + 1]
            }, {
                "x": +t[i + 2],
                "y": +t[i + 3]
            }, {
                "x": +t[i + 4],
                "y": +t[i + 5]
            }];
            e ? i ? n - 4 == i ? a[3] = {
                "x": +t[0],
                "y": +t[1]
            } : n - 2 == i && (a[2] = {
                "x": +t[0],
                "y": +t[1]
            }, a[3] = {
                "x": +t[2],
                "y": +t[3]
            }) : a[0] = {
                "x": +t[n - 2],
                "y": +t[n - 1]
            } : n - 4 == i ? a[3] = a[2] : i || (a[0] = {
                "x": +t[i],
                "y": +t[i + 1]
            }), r.push(["C", (-a[0].x + 6 * a[1].x + a[2].x) / 6, (-a[0].y + 6 * a[1].y + a[2].y) / 6, (a[1].x + 6 * a[2].x - a[3].x) / 6, (a[1].y + 6 * a[2].y - a[3].y) / 6, a[2].x, a[2].y])
        }
        return r
    }

    function o(t, e, r, i, n) {
        var a = -3 * e + 9 * r - 9 * i + 3 * n,
            s = t * a + 6 * e - 12 * r + 6 * i;
        return t * s - 3 * e + 3 * r
    }

    function l(t, e, r, i, n, a, s, l, h) {
        null == h && (h = 1), h = h > 1 ? 1 : 0 > h ? 0 : h;
        for (var u = h / 2, c = 12, f = [-.1252, .1252, -.3678, .3678, -.5873, .5873, -.7699, .7699, -.9041, .9041, -.9816, .9816], p = [.2491, .2491, .2335, .2335, .2032, .2032, .1601, .1601, .1069, .1069, .0472, .0472], d = 0, g = 0; c > g; g++) {
            var x = u * f[g] + u,
                v = o(x, t, r, n, s),
                y = o(x, e, i, a, l),
                m = v * v + y * y;
            d += p[g] * I.sqrt(m)
        }
        return u * d
    }

    function h(t, e, r, i, n, a, s, o, h) {
        if (!(0 > h || l(t, e, r, i, n, a, s, o) < h)) {
            var u, c = 1,
                f = c / 2,
                p = c - f,
                d = .01;
            for (u = l(t, e, r, i, n, a, s, o, p); V(u - h) > d;) f /= 2, p += (h > u ? 1 : -1) * f, u = l(t, e, r, i, n, a, s, o, p);
            return p
        }
    }

    function u(t, e, r, i, n, a, s, o) {
        if (!(q(t, r) < D(n, s) || D(t, r) > q(n, s) || q(e, i) < D(a, o) || D(e, i) > q(a, o))) {
            var l = (t * i - e * r) * (n - s) - (t - r) * (n * o - a * s),
                h = (t * i - e * r) * (a - o) - (e - i) * (n * o - a * s),
                u = (t - r) * (a - o) - (e - i) * (n - s);
            if (u) {
                var c = l / u,
                    f = h / u,
                    p = +c.toFixed(2),
                    d = +f.toFixed(2);
                if (!(p < +D(t, r).toFixed(2) || p > +q(t, r).toFixed(2) || p < +D(n, s).toFixed(2) || p > +q(n, s).toFixed(2) || d < +D(e, i).toFixed(2) || d > +q(e, i).toFixed(2) || d < +D(a, o).toFixed(2) || d > +q(a, o).toFixed(2))) return {
                    "x": c,
                    "y": f
                }
            }
        }
    }

    function c(t, r, i) {
        var n = e.bezierBBox(t),
            a = e.bezierBBox(r);
        if (!e.isBBoxIntersect(n, a)) return i ? 0 : [];
        for (var s = l.apply(0, t), o = l.apply(0, r), h = q(~~(s / 5), 1), c = q(~~(o / 5), 1), f = [], p = [], d = {}, g = i ? 0 : [], x = 0; h + 1 > x; x++) {
            var v = e.findDotsAtSegment.apply(e, t.concat(x / h));
            f.push({
                "x": v.x,
                "y": v.y,
                "t": x / h
            })
        }
        for (x = 0; c + 1 > x; x++) v = e.findDotsAtSegment.apply(e, r.concat(x / c)), p.push({
            "x": v.x,
            "y": v.y,
            "t": x / c
        });
        for (x = 0; h > x; x++)
            for (var y = 0; c > y; y++) {
                var m = f[x],
                    b = f[x + 1],
                    _ = p[y],
                    w = p[y + 1],
                    k = V(b.x - m.x) < .001 ? "y" : "x",
                    B = V(w.x - _.x) < .001 ? "y" : "x",
                    C = u(m.x, m.y, b.x, b.y, _.x, _.y, w.x, w.y);
                if (C) {
                    if (d[C.x.toFixed(4)] == C.y.toFixed(4)) continue;
                    d[C.x.toFixed(4)] = C.y.toFixed(4);
                    var S = m.t + V((C[k] - m[k]) / (b[k] - m[k])) * (b.t - m.t),
                        T = _.t + V((C[B] - _[B]) / (w[B] - _[B])) * (w.t - _.t);
                    S >= 0 && 1.001 >= S && T >= 0 && 1.001 >= T && (i ? g++ : g.push({
                        "x": C.x,
                        "y": C.y,
                        "t1": D(S, 1),
                        "t2": D(T, 1)
                    }))
                }
            }
        return g
    }

    function f(t, r, i) {
        t = e._path2curve(t), r = e._path2curve(r);
        for (var n, a, s, o, l, h, u, f, p, d, g = i ? 0 : [], x = 0, v = t.length; v > x; x++) {
            var y = t[x];
            if ("M" == y[0]) n = l = y[1], a = h = y[2];
            else {
                "C" == y[0] ? (p = [n, a].concat(y.slice(1)), n = p[6], a = p[7]) : (p = [n, a, n, a, l, h, l, h], n = l, a = h);
                for (var m = 0, b = r.length; b > m; m++) {
                    var _ = r[m];
                    if ("M" == _[0]) s = u = _[1], o = f = _[2];
                    else {
                        "C" == _[0] ? (d = [s, o].concat(_.slice(1)), s = d[6], o = d[7]) : (d = [s, o, s, o, u, f, u, f], s = u, o = f);
                        var w = c(p, d, i);
                        if (i) g += w;
                        else {
                            for (var k = 0, B = w.length; B > k; k++) w[k].segment1 = x, w[k].segment2 = m, w[k].bez1 = p, w[k].bez2 = d;
                            g = g.concat(w)
                        }
                    }
                }
            }
        }
        return g
    }

    function p(t, e, r, i, n, a) {
        null != t ? (this.a = +t, this.b = +e, this.c = +r, this.d = +i, this.e = +n, this.f = +a) : (this.a = 1, this.b = 0, this.c = 0, this.d = 1, this.e = 0, this.f = 0)
    }

    function d() {
        return this.x + L + this.y + L + this.width + " \xd7 " + this.height
    }

    function g(t, e, r, i, n, a) {
        function s(t) {
            return ((c * t + u) * t + h) * t
        }

        function o(t, e) {
            var r = l(t, e);
            return ((d * r + p) * r + f) * r
        }

        function l(t, e) {
            var r, i, n, a, o, l;
            for (n = t, l = 0; 8 > l; l++) {
                if (a = s(n) - t, V(a) < e) return n;
                if (o = (3 * c * n + 2 * u) * n + h, V(o) < 1e-6) break;
                n -= a / o
            }
            if (r = 0, i = 1, n = t, r > n) return r;
            if (n > i) return i;
            for (; i > r;) {
                if (a = s(n), V(a - t) < e) return n;
                t > a ? r = n : i = n, n = (i - r) / 2 + r
            }
            return n
        }
        var h = 3 * e,
            u = 3 * (i - e) - h,
            c = 1 - h - u,
            f = 3 * r,
            p = 3 * (n - r) - f,
            d = 1 - f - p;
        return o(t, 1 / (200 * a))
    }

    function x(t, e) {
        var r = [],
            i = {};
        if (this.ms = e, this.times = 1, t) {
            for (var n in t) t[B](n) && (i[J(n)] = t[n], r.push(J(n)));
            r.sort(ue)
        }
        this.anim = i, this.top = r[r.length - 1], this.percents = r
    }

    function v(r, i, n, a, s, o) {
        n = J(n);
        var l, h, u, c, f, d, x = r.ms,
            v = {},
            y = {},
            m = {};
        if (a)
            for (w = 0, k = ar.length; k > w; w++) {
                var b = ar[w];
                if (b.el.id == i.id && b.anim == r) {
                    b.percent != n ? (ar.splice(w, 1), u = 1) : h = b, i.attr(b.totalOrigin);
                    break
                }
            } else a = +y;
        for (var w = 0, k = r.percents.length; k > w; w++) {
            if (r.percents[w] == n || r.percents[w] > a * r.top) {
                n = r.percents[w], f = r.percents[w - 1] || 0, x = x / r.top * (n - f), c = r.percents[w + 1], l = r.anim[n];
                break
            }
            a && i.attr(r.anim[r.percents[w]])
        }
        if (l) {
            if (h) h.initstatus = a, h.start = new Date - h.ms * a;
            else {
                for (var C in l)
                    if (l[B](C) && (re[B](C) || i.paper.customAttributes[B](C))) switch (v[C] = i.attr(C), null == v[C] && (v[C] = ee[C]), y[C] = l[C], re[C]) {
                        case W:
                            m[C] = (y[C] - v[C]) / x;
                            break;
                        case "colour":
                            v[C] = e.getRGB(v[C]);
                            var S = e.getRGB(y[C]);
                            m[C] = {
                                "r": (S.r - v[C].r) / x,
                                "g": (S.g - v[C].g) / x,
                                "b": (S.b - v[C].b) / x
                            };
                            break;
                        case "path":
                            var T = Pe(v[C], y[C]),
                                A = T[1];
                            for (v[C] = T[0], m[C] = [], w = 0, k = v[C].length; k > w; w++) {
                                m[C][w] = [0];
                                for (var N = 1, M = v[C][w].length; M > N; N++) m[C][w][N] = (A[w][N] - v[C][w][N]) / x
                            }
                            break;
                        case "transform":
                            var L = i._,
                                F = qe(L[C], y[C]);
                            if (F)
                                for (v[C] = F.from, y[C] = F.to, m[C] = [], m[C].real = !0, w = 0, k = v[C].length; k > w; w++)
                                    for (m[C][w] = [v[C][w][0]], N = 1, M = v[C][w].length; M > N; N++) m[C][w][N] = (y[C][w][N] - v[C][w][N]) / x;
                            else {
                                var R = i.matrix || new p,
                                    j = {
                                        "_": {
                                            "transform": L.transform
                                        },
                                        "getBBox": function () {
                                            return i.getBBox(1)
                                        }
                                    };
                                v[C] = [R.a, R.b, R.c, R.d, R.e, R.f], je(j, y[C]), y[C] = j._.transform, m[C] = [(j.matrix.a - R.a) / x, (j.matrix.b - R.b) / x, (j.matrix.c - R.c) / x, (j.matrix.d - R.d) / x, (j.matrix.e - R.e) / x, (j.matrix.f - R.f) / x]
                            }
                            break;
                        case "csv":
                            var I = z(l[C])[P](_),
                                q = z(v[C])[P](_);
                            if ("clip-rect" == C)
                                for (v[C] = q, m[C] = [], w = q.length; w--;) m[C][w] = (I[w] - v[C][w]) / x;
                            y[C] = I;
                            break;
                        default:
                            for (I = [][E](l[C]), q = [][E](v[C]), m[C] = [], w = i.paper.customAttributes[C].length; w--;) m[C][w] = ((I[w] || 0) - (q[w] || 0)) / x
                    }
                var D = l.easing,
                    V = e.easing_formulas[D];
                if (!V)
                    if (V = z(D).match(Z), V && 5 == V.length) {
                        var O = V;
                        V = function (t) {
                            return g(t, +O[1], +O[2], +O[3], +O[4], x)
                        }
                    } else V = ce;
                if (d = l.start || r.start || +new Date, b = {
                        "anim": r,
                        "percent": n,
                        "timestamp": d,
                        "start": d + (r.del || 0),
                        "status": 0,
                        "initstatus": a || 0,
                        "stop": !1,
                        "ms": x,
                        "easing": V,
                        "from": v,
                        "diff": m,
                        "to": y,
                        "el": i,
                        "callback": l.callback,
                        "prev": f,
                        "next": c,
                        "repeat": o || r.times,
                        "origin": i.attr(),
                        "totalOrigin": s
                    }, ar.push(b), a && !h && !u && (b.stop = !0, b.start = new Date - x * a, 1 == ar.length)) return or();
                u && (b.start = new Date - b.ms * a), 1 == ar.length && sr(or)
            }
            t("raphael.anim.start." + i.id, i, r)
        }
    }

    function y(t) {
        for (var e = 0; e < ar.length; e++) ar[e].el.paper == t && ar.splice(e--, 1)
    }
    e.version = "2.1.4", e.eve = t;
    var m, b, _ = /[, ]+/,
        w = {
            "circle": 1,
            "rect": 1,
            "path": 1,
            "ellipse": 1,
            "text": 1,
            "image": 1
        },
        k = /\{(\d+)\}/g,
        B = "hasOwnProperty",
        C = {
            "doc": document,
            "win": window
        },
        S = {
            "was": Object.prototype[B].call(C.win, "Raphael"),
            "is": C.win.Raphael
        },
        T = function () {
            this.ca = this.customAttributes = {}
        },
        A = "apply",
        E = "concat",
        N = "ontouchstart" in C.win || C.win.DocumentTouch && C.doc instanceof DocumentTouch,
        M = "",
        L = " ",
        z = String,
        P = "split",
        F = "click dblclick mousedown mousemove mouseout mouseover mouseup touchstart touchmove touchend touchcancel" [P](L),
        R = {
            "mousedown": "touchstart",
            "mousemove": "touchmove",
            "mouseup": "touchend"
        },
        j = z.prototype.toLowerCase,
        I = Math,
        q = I.max,
        D = I.min,
        V = I.abs,
        O = I.pow,
        Y = I.PI,
        W = "number",
        G = "string",
        H = "array",
        X = Object.prototype.toString,
        U = (e._ISURL = /^url\(['"]?(.+?)['"]?\)$/i, /^\s*((#[a-f\d]{6})|(#[a-f\d]{3})|rgba?\(\s*([\d\.]+%?\s*,\s*[\d\.]+%?\s*,\s*[\d\.]+%?(?:\s*,\s*[\d\.]+%?)?)\s*\)|hsba?\(\s*([\d\.]+(?:deg|\xb0|%)?\s*,\s*[\d\.]+%?\s*,\s*[\d\.]+(?:%?\s*,\s*[\d\.]+)?)%?\s*\)|hsla?\(\s*([\d\.]+(?:deg|\xb0|%)?\s*,\s*[\d\.]+%?\s*,\s*[\d\.]+(?:%?\s*,\s*[\d\.]+)?)%?\s*\))\s*$/i),
        $ = {
            "NaN": 1,
            "Infinity": 1,
            "-Infinity": 1
        },
        Z = /^(?:cubic-)?bezier\(([^,]+),([^,]+),([^,]+),([^\)]+)\)/,
        Q = I.round,
        J = parseFloat,
        K = parseInt,
        te = z.prototype.toUpperCase,
        ee = e._availableAttrs = {
            "arrow-end": "none",
            "arrow-start": "none",
            "blur": 0,
            "clip-rect": "0 0 1e9 1e9",
            "cursor": "default",
            "cx": 0,
            "cy": 0,
            "fill": "#fff",
            "fill-opacity": 1,
            "font": '10px "Arial"',
            "font-family": '"Arial"',
            "font-size": "10",
            "font-style": "normal",
            "font-weight": 400,
            "gradient": 0,
            "height": 0,
            "href": "http://raphaeljs.com/",
            "letter-spacing": 0,
            "opacity": 1,
            "path": "M0,0",
            "r": 0,
            "rx": 0,
            "ry": 0,
            "src": "",
            "stroke": "#000",
            "stroke-dasharray": "",
            "stroke-linecap": "butt",
            "stroke-linejoin": "butt",
            "stroke-miterlimit": 0,
            "stroke-opacity": 1,
            "stroke-width": 1,
            "target": "_blank",
            "text-anchor": "middle",
            "title": "Raphael",
            "transform": "",
            "width": 0,
            "x": 0,
            "y": 0
        },
        re = e._availableAnimAttrs = {
            "blur": W,
            "clip-rect": "csv",
            "cx": W,
            "cy": W,
            "fill": "colour",
            "fill-opacity": W,
            "font-size": W,
            "height": W,
            "opacity": W,
            "path": "path",
            "r": W,
            "rx": W,
            "ry": W,
            "stroke": "colour",
            "stroke-opacity": W,
            "stroke-width": W,
            "transform": "transform",
            "width": W,
            "x": W,
            "y": W
        },
        ie = /[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*,[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*/,
        ne = {
            "hs": 1,
            "rg": 1
        },
        ae = /,?([achlmqrstvxz]),?/gi,
        se = /([achlmrqstvz])[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029,]*((-?\d*\.?\d*(?:e[\-+]?\d+)?[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*,?[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*)+)/gi,
        oe = /([rstm])[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029,]*((-?\d*\.?\d*(?:e[\-+]?\d+)?[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*,?[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*)+)/gi,
        le = /(-?\d*\.?\d*(?:e[\-+]?\d+)?)[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*,?[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*/gi,
        he = (e._radial_gradient = /^r(?:\(([^,]+?)[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*,[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*([^\)]+?)\))?/, {}),
        ue = function (t, e) {
            return J(t) - J(e)
        },
        ce = function (t) {
            return t
        },
        fe = e._rectPath = function (t, e, r, i, n) {
            return n ? [
                ["M", t + n, e],
                ["l", r - 2 * n, 0],
                ["a", n, n, 0, 0, 1, n, n],
                ["l", 0, i - 2 * n],
                ["a", n, n, 0, 0, 1, -n, n],
                ["l", 2 * n - r, 0],
                ["a", n, n, 0, 0, 1, -n, -n],
                ["l", 0, 2 * n - i],
                ["a", n, n, 0, 0, 1, n, -n],
                ["z"]
            ] : [
                ["M", t, e],
                ["l", r, 0],
                ["l", 0, i],
                ["l", -r, 0],
                ["z"]
            ]
        },
        pe = function (t, e, r, i) {
            return null == i && (i = r), [
                ["M", t, e],
                ["m", 0, -i],
                ["a", r, i, 0, 1, 1, 0, 2 * i],
                ["a", r, i, 0, 1, 1, 0, -2 * i],
                ["z"]
            ]
        },
        de = e._getPath = {
            "path": function (t) {
                return t.attr("path")
            },
            "circle": function (t) {
                var e = t.attrs;
                return pe(e.cx, e.cy, e.r)
            },
            "ellipse": function (t) {
                var e = t.attrs;
                return pe(e.cx, e.cy, e.rx, e.ry)
            },
            "rect": function (t) {
                var e = t.attrs;
                return fe(e.x, e.y, e.width, e.height, e.r)
            },
            "image": function (t) {
                var e = t.attrs;
                return fe(e.x, e.y, e.width, e.height)
            },
            "text": function (t) {
                var e = t._getBBox();
                return fe(e.x, e.y, e.width, e.height)
            },
            "set": function (t) {
                var e = t._getBBox();
                return fe(e.x, e.y, e.width, e.height)
            }
        },
        ge = e.mapPath = function (t, e) {
            if (!e) return t;
            var r, i, n, a, s, o, l;
            for (t = Pe(t), n = 0, s = t.length; s > n; n++)
                for (l = t[n], a = 1, o = l.length; o > a; a += 2) r = e.x(l[a], l[a + 1]), i = e.y(l[a], l[a + 1]), l[a] = r, l[a + 1] = i;
            return t
        };
    if (e._g = C, e.type = C.win.SVGAngle || C.doc.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#BasicStructure", "1.1") ? "SVG" : "VML", "VML" == e.type) {
        var xe, ve = C.doc.createElement("div");
        if (ve.innerHTML = '<v:shape adj="1"/>', xe = ve.firstChild, xe.style.behavior = "url(#default#VML)", !xe || "object" != typeof xe.adj) return e.type = M;
        ve = null
    }
    e.svg = !(e.vml = "VML" == e.type), e._Paper = T, e.fn = b = T.prototype = e.prototype, e._id = 0, e._oid = 0, e.is = function (t, e) {
        return e = j.call(e), "finite" == e ? !$[B](+t) : "array" == e ? t instanceof Array : "null" == e && null === t || e == typeof t && null !== t || "object" == e && t === Object(t) || "array" == e && Array.isArray && Array.isArray(t) || X.call(t).slice(8, -1).toLowerCase() == e
    }, e.angle = function (t, r, i, n, a, s) {
        if (null == a) {
            var o = t - i,
                l = r - n;
            return o || l ? (180 + 180 * I.atan2(-l, -o) / Y + 360) % 360 : 0
        }
        return e.angle(t, r, a, s) - e.angle(i, n, a, s)
    }, e.rad = function (t) {
        return t % 360 * Y / 180
    }, e.deg = function (t) {
        return Math.round(180 * t / Y % 360 * 1e3) / 1e3
    }, e.snapTo = function (t, r, i) {
        if (i = e.is(i, "finite") ? i : 10, e.is(t, H)) {
            for (var n = t.length; n--;)
                if (V(t[n] - r) <= i) return t[n]
        } else {
            t = +t;
            var a = r % t;
            if (i > a) return r - a;
            if (a > t - i) return r - a + t
        }
        return r
    }, e.createUUID = function (t, e) {
        return function () {
            return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(t, e).toUpperCase()
        }
    }(/[xy]/g, function (t) {
        var e = 16 * I.random() | 0,
            r = "x" == t ? e : 3 & e | 8;
        return r.toString(16)
    }), e.setWindow = function (r) {
        t("raphael.setWindow", e, C.win, r), C.win = r, C.doc = C.win.document, e._engine.initWin && e._engine.initWin(C.win)
    };
    var ye = function (t) {
            if (e.vml) {
                var r, i = /^\s+|\s+$/g;
                try {
                    var a = new ActiveXObject("htmlfile");
                    a.write("<body>"), a.close(), r = a.body
                } catch (s) {
                    r = createPopup().document.body
                }
                var o = r.createTextRange();
                ye = n(function (t) {
                    try {
                        r.style.color = z(t).replace(i, M);
                        var e = o.queryCommandValue("ForeColor");
                        return e = (255 & e) << 16 | 65280 & e | (16711680 & e) >>> 16, "#" + ("000000" + e.toString(16)).slice(-6)
                    } catch (n) {
                        return "none"
                    }
                })
            } else {
                var l = C.doc.createElement("i");
                l.title = "Rapha\xebl Colour Picker", l.style.display = "none", C.doc.body.appendChild(l), ye = n(function (t) {
                    return l.style.color = t, C.doc.defaultView.getComputedStyle(l, M).getPropertyValue("color")
                })
            }
            return ye(t)
        },
        me = function () {
            return "hsb(" + [this.h, this.s, this.b] + ")"
        },
        be = function () {
            return "hsl(" + [this.h, this.s, this.l] + ")"
        },
        _e = function () {
            return this.hex
        },
        we = function (t, r, i) {
            if (null == r && e.is(t, "object") && "r" in t && "g" in t && "b" in t && (i = t.b, r = t.g, t = t.r), null == r && e.is(t, G)) {
                var n = e.getRGB(t);
                t = n.r, r = n.g, i = n.b
            }
            return (t > 1 || r > 1 || i > 1) && (t /= 255, r /= 255, i /= 255), [t, r, i]
        },
        ke = function (t, r, i, n) {
            t *= 255, r *= 255, i *= 255;
            var a = {
                "r": t,
                "g": r,
                "b": i,
                "hex": e.rgb(t, r, i),
                "toString": _e
            };
            return e.is(n, "finite") && (a.opacity = n), a
        };
    e.color = function (t) {
        var r;
        return e.is(t, "object") && "h" in t && "s" in t && "b" in t ? (r = e.hsb2rgb(t), t.r = r.r, t.g = r.g, t.b = r.b, t.hex = r.hex) : e.is(t, "object") && "h" in t && "s" in t && "l" in t ? (r = e.hsl2rgb(t), t.r = r.r, t.g = r.g, t.b = r.b, t.hex = r.hex) : (e.is(t, "string") && (t = e.getRGB(t)), e.is(t, "object") && "r" in t && "g" in t && "b" in t ? (r = e.rgb2hsl(t), t.h = r.h, t.s = r.s, t.l = r.l, r = e.rgb2hsb(t), t.v = r.b) : (t = {
            "hex": "none"
        }, t.r = t.g = t.b = t.h = t.s = t.v = t.l = -1)), t.toString = _e, t
    }, e.hsb2rgb = function (t, e, r, i) {
        this.is(t, "object") && "h" in t && "s" in t && "b" in t && (r = t.b, e = t.s, i = t.o, t = t.h), t *= 360;
        var n, a, s, o, l;
        return t = t % 360 / 60, l = r * e, o = l * (1 - V(t % 2 - 1)), n = a = s = r - l, t = ~~t, n += [l, o, 0, 0, o, l][t], a += [o, l, l, o, 0, 0][t], s += [0, 0, o, l, l, o][t], ke(n, a, s, i)
    }, e.hsl2rgb = function (t, e, r, i) {
        this.is(t, "object") && "h" in t && "s" in t && "l" in t && (r = t.l, e = t.s, t = t.h), (t > 1 || e > 1 || r > 1) && (t /= 360, e /= 100, r /= 100), t *= 360;
        var n, a, s, o, l;
        return t = t % 360 / 60, l = 2 * e * (.5 > r ? r : 1 - r), o = l * (1 - V(t % 2 - 1)), n = a = s = r - l / 2, t = ~~t, n += [l, o, 0, 0, o, l][t], a += [o, l, l, o, 0, 0][t], s += [0, 0, o, l, l, o][t], ke(n, a, s, i)
    }, e.rgb2hsb = function (t, e, r) {
        r = we(t, e, r), t = r[0], e = r[1], r = r[2];
        var i, n, a, s;
        return a = q(t, e, r), s = a - D(t, e, r), i = 0 == s ? null : a == t ? (e - r) / s : a == e ? (r - t) / s + 2 : (t - e) / s + 4, i = (i + 360) % 6 * 60 / 360, n = 0 == s ? 0 : s / a, {
            "h": i,
            "s": n,
            "b": a,
            "toString": me
        }
    }, e.rgb2hsl = function (t, e, r) {
        r = we(t, e, r), t = r[0], e = r[1], r = r[2];
        var i, n, a, s, o, l;
        return s = q(t, e, r), o = D(t, e, r), l = s - o, i = 0 == l ? null : s == t ? (e - r) / l : s == e ? (r - t) / l + 2 : (t - e) / l + 4, i = (i + 360) % 6 * 60 / 360, a = (s + o) / 2, n = 0 == l ? 0 : .5 > a ? l / (2 * a) : l / (2 - 2 * a), {
            "h": i,
            "s": n,
            "l": a,
            "toString": be
        }
    }, e._path2string = function () {
        return this.join(",").replace(ae, "$1")
    }, e._preload = function (t, e) {
        var r = C.doc.createElement("img");
        r.style.cssText = "position:absolute;left:-9999em;top:-9999em", r.onload = function () {
            e.call(this), this.onload = null, C.doc.body.removeChild(this)
        }, r.onerror = function () {
            C.doc.body.removeChild(this)
        }, C.doc.body.appendChild(r), r.src = t
    }, e.getRGB = n(function (t) {
        if (!t || (t = z(t)).indexOf("-") + 1) return {
            "r": -1,
            "g": -1,
            "b": -1,
            "hex": "none",
            "error": 1,
            "toString": a
        };
        if ("none" == t) return {
            "r": -1,
            "g": -1,
            "b": -1,
            "hex": "none",
            "toString": a
        };
        !(ne[B](t.toLowerCase().substring(0, 2)) || "#" == t.charAt()) && (t = ye(t));
        var r, i, n, s, o, l, h = t.match(U);
        return h ? (h[2] && (n = K(h[2].substring(5), 16), i = K(h[2].substring(3, 5), 16), r = K(h[2].substring(1, 3), 16)), h[3] && (n = K((o = h[3].charAt(3)) + o, 16), i = K((o = h[3].charAt(2)) + o, 16), r = K((o = h[3].charAt(1)) + o, 16)), h[4] && (l = h[4][P](ie), r = J(l[0]), "%" == l[0].slice(-1) && (r *= 2.55), i = J(l[1]), "%" == l[1].slice(-1) && (i *= 2.55), n = J(l[2]), "%" == l[2].slice(-1) && (n *= 2.55), "rgba" == h[1].toLowerCase().slice(0, 4) && (s = J(l[3])), l[3] && "%" == l[3].slice(-1) && (s /= 100)), h[5] ? (l = h[5][P](ie), r = J(l[0]), "%" == l[0].slice(-1) && (r *= 2.55), i = J(l[1]), "%" == l[1].slice(-1) && (i *= 2.55), n = J(l[2]), "%" == l[2].slice(-1) && (n *= 2.55), ("deg" == l[0].slice(-3) || "\xb0" == l[0].slice(-1)) && (r /= 360), "hsba" == h[1].toLowerCase().slice(0, 4) && (s = J(l[3])), l[3] && "%" == l[3].slice(-1) && (s /= 100), e.hsb2rgb(r, i, n, s)) : h[6] ? (l = h[6][P](ie), r = J(l[0]), "%" == l[0].slice(-1) && (r *= 2.55), i = J(l[1]), "%" == l[1].slice(-1) && (i *= 2.55), n = J(l[2]), "%" == l[2].slice(-1) && (n *= 2.55), ("deg" == l[0].slice(-3) || "\xb0" == l[0].slice(-1)) && (r /= 360), "hsla" == h[1].toLowerCase().slice(0, 4) && (s = J(l[3])), l[3] && "%" == l[3].slice(-1) && (s /= 100), e.hsl2rgb(r, i, n, s)) : (h = {
            "r": r,
            "g": i,
            "b": n,
            "toString": a
        }, h.hex = "#" + (16777216 | n | i << 8 | r << 16).toString(16).slice(1), e.is(s, "finite") && (h.opacity = s), h)) : {
            "r": -1,
            "g": -1,
            "b": -1,
            "hex": "none",
            "error": 1,
            "toString": a
        }
    }, e), e.hsb = n(function (t, r, i) {
        return e.hsb2rgb(t, r, i).hex
    }), e.hsl = n(function (t, r, i) {
        return e.hsl2rgb(t, r, i).hex
    }), e.rgb = n(function (t, e, r) {
        function i(t) {
            return t + .5 | 0
        }
        return "#" + (16777216 | i(r) | i(e) << 8 | i(t) << 16).toString(16).slice(1)
    }), e.getColor = function (t) {
        var e = this.getColor.start = this.getColor.start || {
                "h": 0,
                "s": 1,
                "b": t || .75
            },
            r = this.hsb2rgb(e.h, e.s, e.b);
        return e.h += .075, e.h > 1 && (e.h = 0, e.s -= .2, e.s <= 0 && (this.getColor.start = {
            "h": 0,
            "s": 1,
            "b": e.b
        })), r.hex
    }, e.getColor.reset = function () {
        delete this.start
    }, e.parsePathString = function (t) {
        if (!t) return null;
        var r = Be(t);
        if (r.arr) return Se(r.arr);
        var i = {
                "a": 7,
                "c": 6,
                "h": 1,
                "l": 2,
                "m": 2,
                "r": 4,
                "q": 4,
                "s": 4,
                "t": 2,
                "v": 1,
                "z": 0
            },
            n = [];
        return e.is(t, H) && e.is(t[0], H) && (n = Se(t)), n.length || z(t).replace(se, function (t, e, r) {
            var a = [],
                s = e.toLowerCase();
            if (r.replace(le, function (t, e) {
                    e && a.push(+e)
                }), "m" == s && a.length > 2 && (n.push([e][E](a.splice(0, 2))), s = "l", e = "m" == e ? "l" : "L"), "r" == s) n.push([e][E](a));
            else
                for (; a.length >= i[s] && (n.push([e][E](a.splice(0, i[s]))), i[s]););
        }), n.toString = e._path2string, r.arr = Se(n), n
    }, e.parseTransformString = n(function (t) {
        if (!t) return null;
        var r = [];
        return e.is(t, H) && e.is(t[0], H) && (r = Se(t)), r.length || z(t).replace(oe, function (t, e, i) {
            var n = [];
            j.call(e), i.replace(le, function (t, e) {
                e && n.push(+e)
            }), r.push([e][E](n))
        }), r.toString = e._path2string, r
    });
    var Be = function (t) {
        var e = Be.ps = Be.ps || {};
        return e[t] ? e[t].sleep = 100 : e[t] = {
            "sleep": 100
        }, setTimeout(function () {
            for (var r in e) e[B](r) && r != t && (e[r].sleep--, !e[r].sleep && delete e[r])
        }), e[t]
    };
    e.findDotsAtSegment = function (t, e, r, i, n, a, s, o, l) {
        var h = 1 - l,
            u = O(h, 3),
            c = O(h, 2),
            f = l * l,
            p = f * l,
            d = u * t + 3 * c * l * r + 3 * h * l * l * n + p * s,
            g = u * e + 3 * c * l * i + 3 * h * l * l * a + p * o,
            x = t + 2 * l * (r - t) + f * (n - 2 * r + t),
            v = e + 2 * l * (i - e) + f * (a - 2 * i + e),
            y = r + 2 * l * (n - r) + f * (s - 2 * n + r),
            m = i + 2 * l * (a - i) + f * (o - 2 * a + i),
            b = h * t + l * r,
            _ = h * e + l * i,
            w = h * n + l * s,
            k = h * a + l * o,
            B = 90 - 180 * I.atan2(x - y, v - m) / Y;
        return (x > y || m > v) && (B += 180), {
            "x": d,
            "y": g,
            "m": {
                "x": x,
                "y": v
            },
            "n": {
                "x": y,
                "y": m
            },
            "start": {
                "x": b,
                "y": _
            },
            "end": {
                "x": w,
                "y": k
            },
            "alpha": B
        }
    }, e.bezierBBox = function (t, r, i, n, a, s, o, l) {
        e.is(t, "array") || (t = [t, r, i, n, a, s, o, l]);
        var h = ze.apply(null, t);
        return {
            "x": h.min.x,
            "y": h.min.y,
            "x2": h.max.x,
            "y2": h.max.y,
            "width": h.max.x - h.min.x,
            "height": h.max.y - h.min.y
        }
    }, e.isPointInsideBBox = function (t, e, r) {
        return e >= t.x && e <= t.x2 && r >= t.y && r <= t.y2
    }, e.isBBoxIntersect = function (t, r) {
        var i = e.isPointInsideBBox;
        return i(r, t.x, t.y) || i(r, t.x2, t.y) || i(r, t.x, t.y2) || i(r, t.x2, t.y2) || i(t, r.x, r.y) || i(t, r.x2, r.y) || i(t, r.x, r.y2) || i(t, r.x2, r.y2) || (t.x < r.x2 && t.x > r.x || r.x < t.x2 && r.x > t.x) && (t.y < r.y2 && t.y > r.y || r.y < t.y2 && r.y > t.y)
    }, e.pathIntersection = function (t, e) {
        return f(t, e)
    }, e.pathIntersectionNumber = function (t, e) {
        return f(t, e, 1)
    }, e.isPointInsidePath = function (t, r, i) {
        var n = e.pathBBox(t);
        return e.isPointInsideBBox(n, r, i) && f(t, [
            ["M", r, i],
            ["H", n.x2 + 10]
        ], 1) % 2 == 1
    }, e._removedFactory = function (e) {
        return function () {
            t("raphael.log", null, "Rapha\xebl: you are calling to method \u201c" + e + "\u201d of removed object", e)
        }
    };
    var Ce = e.pathBBox = function (t) {
            var e = Be(t);
            if (e.bbox) return r(e.bbox);
            if (!t) return {
                "x": 0,
                "y": 0,
                "width": 0,
                "height": 0,
                "x2": 0,
                "y2": 0
            };
            t = Pe(t);
            for (var i, n = 0, a = 0, s = [], o = [], l = 0, h = t.length; h > l; l++)
                if (i = t[l], "M" == i[0]) n = i[1], a = i[2], s.push(n), o.push(a);
                else {
                    var u = ze(n, a, i[1], i[2], i[3], i[4], i[5], i[6]);
                    s = s[E](u.min.x, u.max.x), o = o[E](u.min.y, u.max.y), n = i[5], a = i[6]
                }
            var c = D[A](0, s),
                f = D[A](0, o),
                p = q[A](0, s),
                d = q[A](0, o),
                g = p - c,
                x = d - f,
                v = {
                    "x": c,
                    "y": f,
                    "x2": p,
                    "y2": d,
                    "width": g,
                    "height": x,
                    "cx": c + g / 2,
                    "cy": f + x / 2
                };
            return e.bbox = r(v), v
        },
        Se = function (t) {
            var i = r(t);
            return i.toString = e._path2string, i
        },
        Te = e._pathToRelative = function (t) {
            var r = Be(t);
            if (r.rel) return Se(r.rel);
            e.is(t, H) && e.is(t && t[0], H) || (t = e.parsePathString(t));
            var i = [],
                n = 0,
                a = 0,
                s = 0,
                o = 0,
                l = 0;
            "M" == t[0][0] && (n = t[0][1], a = t[0][2], s = n, o = a, l++, i.push(["M", n, a]));
            for (var h = l, u = t.length; u > h; h++) {
                var c = i[h] = [],
                    f = t[h];
                if (f[0] != j.call(f[0])) switch (c[0] = j.call(f[0]), c[0]) {
                    case "a":
                        c[1] = f[1], c[2] = f[2], c[3] = f[3], c[4] = f[4], c[5] = f[5], c[6] = +(f[6] - n).toFixed(3), c[7] = +(f[7] - a).toFixed(3);
                        break;
                    case "v":
                        c[1] = +(f[1] - a).toFixed(3);
                        break;
                    case "m":
                        s = f[1], o = f[2];
                    default:
                        for (var p = 1, d = f.length; d > p; p++) c[p] = +(f[p] - (p % 2 ? n : a)).toFixed(3)
                } else {
                    c = i[h] = [], "m" == f[0] && (s = f[1] + n, o = f[2] + a);
                    for (var g = 0, x = f.length; x > g; g++) i[h][g] = f[g]
                }
                var v = i[h].length;
                switch (i[h][0]) {
                    case "z":
                        n = s, a = o;
                        break;
                    case "h":
                        n += +i[h][v - 1];
                        break;
                    case "v":
                        a += +i[h][v - 1];
                        break;
                    default:
                        n += +i[h][v - 2], a += +i[h][v - 1]
                }
            }
            return i.toString = e._path2string, r.rel = Se(i), i
        },
        Ae = e._pathToAbsolute = function (t) {
            var r = Be(t);
            if (r.abs) return Se(r.abs);
            if (e.is(t, H) && e.is(t && t[0], H) || (t = e.parsePathString(t)), !t || !t.length) return [
                ["M", 0, 0]
            ];
            var i = [],
                n = 0,
                a = 0,
                o = 0,
                l = 0,
                h = 0;
            "M" == t[0][0] && (n = +t[0][1], a = +t[0][2], o = n, l = a, h++, i[0] = ["M", n, a]);
            for (var u, c, f = 3 == t.length && "M" == t[0][0] && "R" == t[1][0].toUpperCase() && "Z" == t[2][0].toUpperCase(), p = h, d = t.length; d > p; p++) {
                if (i.push(u = []), c = t[p], c[0] != te.call(c[0])) switch (u[0] = te.call(c[0]), u[0]) {
                        case "A":
                            u[1] = c[1], u[2] = c[2], u[3] = c[3], u[4] = c[4], u[5] = c[5], u[6] = +(c[6] + n), u[7] = +(c[7] + a);
                            break;
                        case "V":
                            u[1] = +c[1] + a;
                            break;
                        case "H":
                            u[1] = +c[1] + n;
                            break;
                        case "R":
                            for (var g = [n, a][E](c.slice(1)), x = 2, v = g.length; v > x; x++) g[x] = +g[x] + n, g[++x] = +g[x] + a;
                            i.pop(), i = i[E](s(g, f));
                            break;
                        case "M":
                            o = +c[1] + n, l = +c[2] + a;
                        default:
                            for (x = 1, v = c.length; v > x; x++) u[x] = +c[x] + (x % 2 ? n : a)
                    } else if ("R" == c[0]) g = [n, a][E](c.slice(1)), i.pop(), i = i[E](s(g, f)), u = ["R"][E](c.slice(-2));
                    else
                        for (var y = 0, m = c.length; m > y; y++) u[y] = c[y];
                switch (u[0]) {
                    case "Z":
                        n = o, a = l;
                        break;
                    case "H":
                        n = u[1];
                        break;
                    case "V":
                        a = u[1];
                        break;
                    case "M":
                        o = u[u.length - 2], l = u[u.length - 1];
                    default:
                        n = u[u.length - 2], a = u[u.length - 1]
                }
            }
            return i.toString = e._path2string, r.abs = Se(i), i
        },
        Ee = function (t, e, r, i) {
            return [t, e, r, i, r, i]
        },
        Ne = function (t, e, r, i, n, a) {
            var s = 1 / 3,
                o = 2 / 3;
            return [s * t + o * r, s * e + o * i, s * n + o * r, s * a + o * i, n, a]
        },
        Me = function (t, e, r, i, a, s, o, l, h, u) {
            var c, f = 120 * Y / 180,
                p = Y / 180 * (+a || 0),
                d = [],
                g = n(function (t, e, r) {
                    var i = t * I.cos(r) - e * I.sin(r),
                        n = t * I.sin(r) + e * I.cos(r);
                    return {
                        "x": i,
                        "y": n
                    }
                });
            if (u) B = u[0], C = u[1], w = u[2], k = u[3];
            else {
                c = g(t, e, -p), t = c.x, e = c.y, c = g(l, h, -p), l = c.x, h = c.y;
                var x = (I.cos(Y / 180 * a), I.sin(Y / 180 * a), (t - l) / 2),
                    v = (e - h) / 2,
                    y = x * x / (r * r) + v * v / (i * i);
                y > 1 && (y = I.sqrt(y), r = y * r, i = y * i);
                var m = r * r,
                    b = i * i,
                    _ = (s == o ? -1 : 1) * I.sqrt(V((m * b - m * v * v - b * x * x) / (m * v * v + b * x * x))),
                    w = _ * r * v / i + (t + l) / 2,
                    k = _ * -i * x / r + (e + h) / 2,
                    B = I.asin(((e - k) / i).toFixed(9)),
                    C = I.asin(((h - k) / i).toFixed(9));
                B = w > t ? Y - B : B, C = w > l ? Y - C : C, 0 > B && (B = 2 * Y + B), 0 > C && (C = 2 * Y + C), o && B > C && (B -= 2 * Y), !o && C > B && (C -= 2 * Y)
            }
            var S = C - B;
            if (V(S) > f) {
                var T = C,
                    A = l,
                    N = h;
                C = B + f * (o && C > B ? 1 : -1), l = w + r * I.cos(C), h = k + i * I.sin(C), d = Me(l, h, r, i, a, 0, o, A, N, [C, T, w, k])
            }
            S = C - B;
            var M = I.cos(B),
                L = I.sin(B),
                z = I.cos(C),
                F = I.sin(C),
                R = I.tan(S / 4),
                j = 4 / 3 * r * R,
                q = 4 / 3 * i * R,
                D = [t, e],
                O = [t + j * L, e - q * M],
                W = [l + j * F, h - q * z],
                G = [l, h];
            if (O[0] = 2 * D[0] - O[0], O[1] = 2 * D[1] - O[1], u) return [O, W, G][E](d);
            d = [O, W, G][E](d).join()[P](",");
            for (var H = [], X = 0, U = d.length; U > X; X++) H[X] = X % 2 ? g(d[X - 1], d[X], p).y : g(d[X], d[X + 1], p).x;
            return H
        },
        Le = function (t, e, r, i, n, a, s, o, l) {
            var h = 1 - l;
            return {
                "x": O(h, 3) * t + 3 * O(h, 2) * l * r + 3 * h * l * l * n + O(l, 3) * s,
                "y": O(h, 3) * e + 3 * O(h, 2) * l * i + 3 * h * l * l * a + O(l, 3) * o
            }
        },
        ze = n(function (t, e, r, i, n, a, s, o) {
            var l, h = n - 2 * r + t - (s - 2 * n + r),
                u = 2 * (r - t) - 2 * (n - r),
                c = t - r,
                f = (-u + I.sqrt(u * u - 4 * h * c)) / 2 / h,
                p = (-u - I.sqrt(u * u - 4 * h * c)) / 2 / h,
                d = [e, o],
                g = [t, s];
            return V(f) > "1e12" && (f = .5), V(p) > "1e12" && (p = .5), f > 0 && 1 > f && (l = Le(t, e, r, i, n, a, s, o, f), g.push(l.x), d.push(l.y)), p > 0 && 1 > p && (l = Le(t, e, r, i, n, a, s, o, p), g.push(l.x), d.push(l.y)), h = a - 2 * i + e - (o - 2 * a + i), u = 2 * (i - e) - 2 * (a - i), c = e - i, f = (-u + I.sqrt(u * u - 4 * h * c)) / 2 / h, p = (-u - I.sqrt(u * u - 4 * h * c)) / 2 / h, V(f) > "1e12" && (f = .5), V(p) > "1e12" && (p = .5), f > 0 && 1 > f && (l = Le(t, e, r, i, n, a, s, o, f), g.push(l.x), d.push(l.y)), p > 0 && 1 > p && (l = Le(t, e, r, i, n, a, s, o, p), g.push(l.x), d.push(l.y)), {
                "min": {
                    "x": D[A](0, g),
                    "y": D[A](0, d)
                },
                "max": {
                    "x": q[A](0, g),
                    "y": q[A](0, d)
                }
            }
        }),
        Pe = e._path2curve = n(function (t, e) {
            var r = !e && Be(t);
            if (!e && r.curve) return Se(r.curve);
            for (var i = Ae(t), n = e && Ae(e), a = {
                    "x": 0,
                    "y": 0,
                    "bx": 0,
                    "by": 0,
                    "X": 0,
                    "Y": 0,
                    "qx": null,
                    "qy": null
                }, s = {
                    "x": 0,
                    "y": 0,
                    "bx": 0,
                    "by": 0,
                    "X": 0,
                    "Y": 0,
                    "qx": null,
                    "qy": null
                }, o = (function (t, e, r) {
                    var i, n, a = {
                        "T": 1,
                        "Q": 1
                    };
                    if (!t) return ["C", e.x, e.y, e.x, e.y, e.x, e.y];
                    switch (!(t[0] in a) && (e.qx = e.qy = null), t[0]) {
                        case "M":
                            e.X = t[1], e.Y = t[2];
                            break;
                        case "A":
                            t = ["C"][E](Me[A](0, [e.x, e.y][E](t.slice(1))));
                            break;
                        case "S":
                            "C" == r || "S" == r ? (i = 2 * e.x - e.bx, n = 2 * e.y - e.by) : (i = e.x, n = e.y), t = ["C", i, n][E](t.slice(1));
                            break;
                        case "T":
                            "Q" == r || "T" == r ? (e.qx = 2 * e.x - e.qx, e.qy = 2 * e.y - e.qy) : (e.qx = e.x, e.qy = e.y), t = ["C"][E](Ne(e.x, e.y, e.qx, e.qy, t[1], t[2]));
                            break;
                        case "Q":
                            e.qx = t[1], e.qy = t[2], t = ["C"][E](Ne(e.x, e.y, t[1], t[2], t[3], t[4]));
                            break;
                        case "L":
                            t = ["C"][E](Ee(e.x, e.y, t[1], t[2]));
                            break;
                        case "H":
                            t = ["C"][E](Ee(e.x, e.y, t[1], e.y));
                            break;
                        case "V":
                            t = ["C"][E](Ee(e.x, e.y, e.x, t[1]));
                            break;
                        case "Z":
                            t = ["C"][E](Ee(e.x, e.y, e.X, e.Y))
                    }
                    return t
                }), l = function (t, e) {
                    if (t[e].length > 7) {
                        t[e].shift();
                        for (var r = t[e]; r.length;) u[e] = "A", n && (c[e] = "A"), t.splice(e++, 0, ["C"][E](r.splice(0, 6)));
                        t.splice(e, 1), g = q(i.length, n && n.length || 0)
                    }
                }, h = function (t, e, r, a, s) {
                    t && e && "M" == t[s][0] && "M" != e[s][0] && (e.splice(s, 0, ["M", a.x, a.y]), r.bx = 0, r.by = 0, r.x = t[s][1], r.y = t[s][2], g = q(i.length, n && n.length || 0))
                }, u = [], c = [], f = "", p = "", d = 0, g = q(i.length, n && n.length || 0); g > d; d++) {
                i[d] && (f = i[d][0]), "C" != f && (u[d] = f, d && (p = u[d - 1])), i[d] = o(i[d], a, p), "A" != u[d] && "C" == f && (u[d] = "C"), l(i, d), n && (n[d] && (f = n[d][0]), "C" != f && (c[d] = f, d && (p = c[d - 1])), n[d] = o(n[d], s, p), "A" != c[d] && "C" == f && (c[d] = "C"), l(n, d)), h(i, n, a, s, d), h(n, i, s, a, d);
                var x = i[d],
                    v = n && n[d],
                    y = x.length,
                    m = n && v.length;
                a.x = x[y - 2], a.y = x[y - 1], a.bx = J(x[y - 4]) || a.x, a.by = J(x[y - 3]) || a.y, s.bx = n && (J(v[m - 4]) || s.x), s.by = n && (J(v[m - 3]) || s.y), s.x = n && v[m - 2], s.y = n && v[m - 1]
            }
            return n || (r.curve = Se(i)), n ? [i, n] : i
        }, null, Se),
        Fe = (e._parseDots = n(function (t) {
            for (var r = [], i = 0, n = t.length; n > i; i++) {
                var a = {},
                    s = t[i].match(/^([^:]*):?([\d\.]*)/);
                if (a.color = e.getRGB(s[1]), a.color.error) return null;
                a.opacity = a.color.opacity, a.color = a.color.hex, s[2] && (a.offset = s[2] + "%"), r.push(a)
            }
            for (i = 1, n = r.length - 1; n > i; i++)
                if (!r[i].offset) {
                    for (var o = J(r[i - 1].offset || 0), l = 0, h = i + 1; n > h; h++)
                        if (r[h].offset) {
                            l = r[h].offset;
                            break
                        }
                    l || (l = 100, h = n), l = J(l);
                    for (var u = (l - o) / (h - i + 1); h > i; i++) o += u, r[i].offset = o + "%"
                }
            return r
        }), e._tear = function (t, e) {
            t == e.top && (e.top = t.prev), t == e.bottom && (e.bottom = t.next), t.next && (t.next.prev = t.prev), t.prev && (t.prev.next = t.next)
        }),
        Re = (e._tofront = function (t, e) {
            e.top !== t && (Fe(t, e), t.next = null, t.prev = e.top, e.top.next = t, e.top = t)
        }, e._toback = function (t, e) {
            e.bottom !== t && (Fe(t, e), t.next = e.bottom, t.prev = null, e.bottom.prev = t, e.bottom = t)
        }, e._insertafter = function (t, e, r) {
            Fe(t, r), e == r.top && (r.top = t), e.next && (e.next.prev = t), t.next = e.next, t.prev = e, e.next = t
        }, e._insertbefore = function (t, e, r) {
            Fe(t, r), e == r.bottom && (r.bottom = t), e.prev && (e.prev.next = t), t.prev = e.prev, e.prev = t, t.next = e
        }, e.toMatrix = function (t, e) {
            var r = Ce(t),
                i = {
                    "_": {
                        "transform": M
                    },
                    "getBBox": function () {
                        return r
                    }
                };
            return je(i, e), i.matrix
        }),
        je = (e.transformPath = function (t, e) {
            return ge(t, Re(t, e))
        }, e._extractTransform = function (t, r) {
            if (null == r) return t._.transform;
            r = z(r).replace(/\.{3}|\u2026/g, t._.transform || M);
            var i = e.parseTransformString(r),
                n = 0,
                a = 0,
                s = 0,
                o = 1,
                l = 1,
                h = t._,
                u = new p;
            if (h.transform = i || [], i)
                for (var c = 0, f = i.length; f > c; c++) {
                    var d, g, x, v, y, m = i[c],
                        b = m.length,
                        _ = z(m[0]).toLowerCase(),
                        w = m[0] != _,
                        k = w ? u.invert() : 0;
                    "t" == _ && 3 == b ? w ? (d = k.x(0, 0), g = k.y(0, 0), x = k.x(m[1], m[2]), v = k.y(m[1], m[2]), u.translate(x - d, v - g)) : u.translate(m[1], m[2]) : "r" == _ ? 2 == b ? (y = y || t.getBBox(1), u.rotate(m[1], y.x + y.width / 2, y.y + y.height / 2), n += m[1]) : 4 == b && (w ? (x = k.x(m[2], m[3]), v = k.y(m[2], m[3]), u.rotate(m[1], x, v)) : u.rotate(m[1], m[2], m[3]), n += m[1]) : "s" == _ ? 2 == b || 3 == b ? (y = y || t.getBBox(1), u.scale(m[1], m[b - 1], y.x + y.width / 2, y.y + y.height / 2), o *= m[1], l *= m[b - 1]) : 5 == b && (w ? (x = k.x(m[3], m[4]), v = k.y(m[3], m[4]), u.scale(m[1], m[2], x, v)) : u.scale(m[1], m[2], m[3], m[4]), o *= m[1], l *= m[2]) : "m" == _ && 7 == b && u.add(m[1], m[2], m[3], m[4], m[5], m[6]), h.dirtyT = 1, t.matrix = u
                }
            t.matrix = u, h.sx = o, h.sy = l, h.deg = n, h.dx = a = u.e, h.dy = s = u.f, 1 == o && 1 == l && !n && h.bbox ? (h.bbox.x += +a, h.bbox.y += +s) : h.dirtyT = 1
        }),
        Ie = function (t) {
            var e = t[0];
            switch (e.toLowerCase()) {
                case "t":
                    return [e, 0, 0];
                case "m":
                    return [e, 1, 0, 0, 1, 0, 0];
                case "r":
                    return 4 == t.length ? [e, 0, t[2], t[3]] : [e, 0];
                case "s":
                    return 5 == t.length ? [e, 1, 1, t[3], t[4]] : 3 == t.length ? [e, 1, 1] : [e, 1]
            }
        },
        qe = e._equaliseTransform = function (t, r) {
            r = z(r).replace(/\.{3}|\u2026/g, t), t = e.parseTransformString(t) || [], r = e.parseTransformString(r) || [];
            for (var i, n, a, s, o = q(t.length, r.length), l = [], h = [], u = 0; o > u; u++) {
                if (a = t[u] || Ie(r[u]), s = r[u] || Ie(a), a[0] != s[0] || "r" == a[0].toLowerCase() && (a[2] != s[2] || a[3] != s[3]) || "s" == a[0].toLowerCase() && (a[3] != s[3] || a[4] != s[4])) return;
                for (l[u] = [], h[u] = [], i = 0, n = q(a.length, s.length); n > i; i++) i in a && (l[u][i] = a[i]), i in s && (h[u][i] = s[i])
            }
            return {
                "from": l,
                "to": h
            }
        };
    e._getContainer = function (t, r, i, n) {
            var a;
            return a = null != n || e.is(t, "object") ? t : C.doc.getElementById(t), null != a ? a.tagName ? null == r ? {
                "container": a,
                "width": a.style.pixelWidth || a.offsetWidth,
                "height": a.style.pixelHeight || a.offsetHeight
            } : {
                "container": a,
                "width": r,
                "height": i
            } : {
                "container": 1,
                "x": t,
                "y": r,
                "width": i,
                "height": n
            } : void 0
        }, e.pathToRelative = Te, e._engine = {}, e.path2curve = Pe, e.matrix = function (t, e, r, i, n, a) {
            return new p(t, e, r, i, n, a)
        },
        function (t) {
            function r(t) {
                return t[0] * t[0] + t[1] * t[1]
            }

            function i(t) {
                var e = I.sqrt(r(t));
                t[0] && (t[0] /= e), t[1] && (t[1] /= e)
            }
            t.add = function (t, e, r, i, n, a) {
                var s, o, l, h, u = [
                        [],
                        [],
                        []
                    ],
                    c = [
                        [this.a, this.c, this.e],
                        [this.b, this.d, this.f],
                        [0, 0, 1]
                    ],
                    f = [
                        [t, r, n],
                        [e, i, a],
                        [0, 0, 1]
                    ];
                for (t && t instanceof p && (f = [
                        [t.a, t.c, t.e],
                        [t.b, t.d, t.f],
                        [0, 0, 1]
                    ]), s = 0; 3 > s; s++)
                    for (o = 0; 3 > o; o++) {
                        for (h = 0, l = 0; 3 > l; l++) h += c[s][l] * f[l][o];
                        u[s][o] = h
                    }
                this.a = u[0][0], this.b = u[1][0], this.c = u[0][1], this.d = u[1][1], this.e = u[0][2], this.f = u[1][2]
            }, t.invert = function () {
                var t = this,
                    e = t.a * t.d - t.b * t.c;
                return new p(t.d / e, -t.b / e, -t.c / e, t.a / e, (t.c * t.f - t.d * t.e) / e, (t.b * t.e - t.a * t.f) / e)
            }, t.clone = function () {
                return new p(this.a, this.b, this.c, this.d, this.e, this.f)
            }, t.translate = function (t, e) {
                this.add(1, 0, 0, 1, t, e)
            }, t.scale = function (t, e, r, i) {
                null == e && (e = t), (r || i) && this.add(1, 0, 0, 1, r, i), this.add(t, 0, 0, e, 0, 0), (r || i) && this.add(1, 0, 0, 1, -r, -i)
            }, t.rotate = function (t, r, i) {
                t = e.rad(t), r = r || 0, i = i || 0;
                var n = +I.cos(t).toFixed(9),
                    a = +I.sin(t).toFixed(9);
                this.add(n, a, -a, n, r, i), this.add(1, 0, 0, 1, -r, -i)
            }, t.x = function (t, e) {
                return t * this.a + e * this.c + this.e
            }, t.y = function (t, e) {
                return t * this.b + e * this.d + this.f
            }, t.get = function (t) {
                return +this[z.fromCharCode(97 + t)].toFixed(4)
            }, t.toString = function () {
                return e.svg ? "matrix(" + [this.get(0), this.get(1), this.get(2), this.get(3), this.get(4), this.get(5)].join() + ")" : [this.get(0), this.get(2), this.get(1), this.get(3), 0, 0].join()
            }, t.toFilter = function () {
                return "progid:DXImageTransform.Microsoft.Matrix(M11=" + this.get(0) + ", M12=" + this.get(2) + ", M21=" + this.get(1) + ", M22=" + this.get(3) + ", Dx=" + this.get(4) + ", Dy=" + this.get(5) + ", sizingmethod='auto expand')"
            }, t.offset = function () {
                return [this.e.toFixed(4), this.f.toFixed(4)]
            }, t.split = function () {
                var t = {};
                t.dx = this.e, t.dy = this.f;
                var n = [
                    [this.a, this.c],
                    [this.b, this.d]
                ];
                t.scalex = I.sqrt(r(n[0])), i(n[0]), t.shear = n[0][0] * n[1][0] + n[0][1] * n[1][1], n[1] = [n[1][0] - n[0][0] * t.shear, n[1][1] - n[0][1] * t.shear], t.scaley = I.sqrt(r(n[1])), i(n[1]), t.shear /= t.scaley;
                var a = -n[0][1],
                    s = n[1][1];
                return 0 > s ? (t.rotate = e.deg(I.acos(s)), 0 > a && (t.rotate = 360 - t.rotate)) : t.rotate = e.deg(I.asin(a)), t.isSimple = !(+t.shear.toFixed(9) || t.scalex.toFixed(9) != t.scaley.toFixed(9) && t.rotate), t.isSuperSimple = !+t.shear.toFixed(9) && t.scalex.toFixed(9) == t.scaley.toFixed(9) && !t.rotate, t.noRotation = !+t.shear.toFixed(9) && !t.rotate, t
            }, t.toTransformString = function (t) {
                var e = t || this[P]();
                return e.isSimple ? (e.scalex = +e.scalex.toFixed(4), e.scaley = +e.scaley.toFixed(4), e.rotate = +e.rotate.toFixed(4), (e.dx || e.dy ? "t" + [e.dx, e.dy] : M) + (1 != e.scalex || 1 != e.scaley ? "s" + [e.scalex, e.scaley, 0, 0] : M) + (e.rotate ? "r" + [e.rotate, 0, 0] : M)) : "m" + [this.get(0), this.get(1), this.get(2), this.get(3), this.get(4), this.get(5)]
            }
        }(p.prototype);
    for (var De = function () {
            this.returnValue = !1
        }, Ve = function () {
            return this.originalEvent.preventDefault()
        }, Oe = function () {
            this.cancelBubble = !0
        }, Ye = function () {
            return this.originalEvent.stopPropagation()
        }, We = function (t) {
            var e = C.doc.documentElement.scrollTop || C.doc.body.scrollTop,
                r = C.doc.documentElement.scrollLeft || C.doc.body.scrollLeft;
            return {
                "x": t.clientX + r,
                "y": t.clientY + e
            }
        }, Ge = function () {
            return C.doc.addEventListener ? function (t, e, r, i) {
                var n = function (t) {
                    var e = We(t);
                    return r.call(i, t, e.x, e.y)
                };
                if (t.addEventListener(e, n, !1), N && R[e]) {
                    var a = function (e) {
                        for (var n = We(e), a = e, s = 0, o = e.targetTouches && e.targetTouches.length; o > s; s++)
                            if (e.targetTouches[s].target == t) {
                                e = e.targetTouches[s], e.originalEvent = a, e.preventDefault = Ve, e.stopPropagation = Ye;
                                break
                            }
                        return r.call(i, e, n.x, n.y)
                    };
                    t.addEventListener(R[e], a, !1)
                }
                return function () {
                    return t.removeEventListener(e, n, !1), N && R[e] && t.removeEventListener(R[e], a, !1), !0
                }
            } : C.doc.attachEvent ? function (t, e, r, i) {
                var n = function (t) {
                    t = t || C.win.event;
                    var e = C.doc.documentElement.scrollTop || C.doc.body.scrollTop,
                        n = C.doc.documentElement.scrollLeft || C.doc.body.scrollLeft,
                        a = t.clientX + n,
                        s = t.clientY + e;
                    return t.preventDefault = t.preventDefault || De, t.stopPropagation = t.stopPropagation || Oe, r.call(i, t, a, s)
                };
                t.attachEvent("on" + e, n);
                var a = function () {
                    return t.detachEvent("on" + e, n), !0
                };
                return a
            } : void 0
        }(), He = [], Xe = function (e) {
            for (var r, i = e.clientX, n = e.clientY, a = C.doc.documentElement.scrollTop || C.doc.body.scrollTop, s = C.doc.documentElement.scrollLeft || C.doc.body.scrollLeft, o = He.length; o--;) {
                if (r = He[o], N && e.touches) {
                    for (var l, h = e.touches.length; h--;)
                        if (l = e.touches[h], l.identifier == r.el._drag.id) {
                            i = l.clientX, n = l.clientY, (e.originalEvent ? e.originalEvent : e).preventDefault();
                            break
                        }
                } else e.preventDefault();
                var u, c = r.el.node,
                    f = c.nextSibling,
                    p = c.parentNode,
                    d = c.style.display;
                C.win.opera && p.removeChild(c), c.style.display = "none", u = r.el.paper.getElementByPoint(i, n), c.style.display = d, C.win.opera && (f ? p.insertBefore(c, f) : p.appendChild(c)), u && t("raphael.drag.over." + r.el.id, r.el, u), i += s, n += a, t("raphael.drag.move." + r.el.id, r.move_scope || r.el, i - r.el._drag.x, n - r.el._drag.y, i, n, e)
            }
        }, Ue = function (r) {
            e.unmousemove(Xe).unmouseup(Ue);
            for (var i, n = He.length; n--;) i = He[n], i.el._drag = {}, t("raphael.drag.end." + i.el.id, i.end_scope || i.start_scope || i.move_scope || i.el, r);
            He = []
        }, $e = e.el = {}, Ze = F.length; Ze--;) ! function (t) {
        e[t] = $e[t] = function (r, i) {
            return e.is(r, "function") && (this.events = this.events || [], this.events.push({
                "name": t,
                "f": r,
                "unbind": Ge(this.shape || this.node || C.doc, t, r, i || this)
            })), this
        }, e["un" + t] = $e["un" + t] = function (r) {
            for (var i = this.events || [], n = i.length; n--;) i[n].name != t || !e.is(r, "undefined") && i[n].f != r || (i[n].unbind(), i.splice(n, 1), !i.length && delete this.events);
            return this
        }
    }(F[Ze]);
    $e.data = function (r, i) {
        var n = he[this.id] = he[this.id] || {};
        if (0 == arguments.length) return n;
        if (1 == arguments.length) {
            if (e.is(r, "object")) {
                for (var a in r) r[B](a) && this.data(a, r[a]);
                return this
            }
            return t("raphael.data.get." + this.id, this, n[r], r), n[r]
        }
        return n[r] = i, t("raphael.data.set." + this.id, this, i, r), this
    }, $e.removeData = function (t) {
        return null == t ? he[this.id] = {} : he[this.id] && delete he[this.id][t], this
    }, $e.getData = function () {
        return r(he[this.id] || {})
    }, $e.hover = function (t, e, r, i) {
        return this.mouseover(t, r).mouseout(e, i || r)
    }, $e.unhover = function (t, e) {
        return this.unmouseover(t).unmouseout(e)
    };
    var Qe = [];
    $e.drag = function (r, i, n, a, s, o) {
        function l(l) {
            (l.originalEvent || l).preventDefault();
            var h = l.clientX,
                u = l.clientY,
                c = C.doc.documentElement.scrollTop || C.doc.body.scrollTop,
                f = C.doc.documentElement.scrollLeft || C.doc.body.scrollLeft;
            if (this._drag.id = l.identifier, N && l.touches)
                for (var p, d = l.touches.length; d--;)
                    if (p = l.touches[d], this._drag.id = p.identifier, p.identifier == this._drag.id) {
                        h = p.clientX, u = p.clientY;
                        break
                    }
            this._drag.x = h + f, this._drag.y = u + c, !He.length && e.mousemove(Xe).mouseup(Ue), He.push({
                "el": this,
                "move_scope": a,
                "start_scope": s,
                "end_scope": o
            }), i && t.on("raphael.drag.start." + this.id, i), r && t.on("raphael.drag.move." + this.id, r), n && t.on("raphael.drag.end." + this.id, n), t("raphael.drag.start." + this.id, s || a || this, l.clientX + f, l.clientY + c, l)
        }
        return this._drag = {}, Qe.push({
            "el": this,
            "start": l
        }), this.mousedown(l), this
    }, $e.onDragOver = function (e) {
        e ? t.on("raphael.drag.over." + this.id, e) : t.unbind("raphael.drag.over." + this.id)
    }, $e.undrag = function () {
        for (var r = Qe.length; r--;) Qe[r].el == this && (this.unmousedown(Qe[r].start), Qe.splice(r, 1), t.unbind("raphael.drag.*." + this.id));
        !Qe.length && e.unmousemove(Xe).unmouseup(Ue), He = []
    }, b.circle = function (t, r, i) {
        var n = e._engine.circle(this, t || 0, r || 0, i || 0);
        return this.__set__ && this.__set__.push(n), n
    }, b.rect = function (t, r, i, n, a) {
        var s = e._engine.rect(this, t || 0, r || 0, i || 0, n || 0, a || 0);
        return this.__set__ && this.__set__.push(s), s
    }, b.ellipse = function (t, r, i, n) {
        var a = e._engine.ellipse(this, t || 0, r || 0, i || 0, n || 0);
        return this.__set__ && this.__set__.push(a), a
    }, b.path = function (t) {
        t && !e.is(t, G) && !e.is(t[0], H) && (t += M);
        var r = e._engine.path(e.format[A](e, arguments), this);
        return this.__set__ && this.__set__.push(r), r
    }, b.image = function (t, r, i, n, a) {
        var s = e._engine.image(this, t || "about:blank", r || 0, i || 0, n || 0, a || 0);
        return this.__set__ && this.__set__.push(s), s
    }, b.text = function (t, r, i) {
        var n = e._engine.text(this, t || 0, r || 0, z(i));
        return this.__set__ && this.__set__.push(n), n
    }, b.set = function (t) {
        !e.is(t, "array") && (t = Array.prototype.splice.call(arguments, 0, arguments.length));
        var r = new hr(t);
        return this.__set__ && this.__set__.push(r), r.paper = this, r.type = "set", r
    }, b.setStart = function (t) {
        this.__set__ = t || this.set()
    }, b.setFinish = function () {
        var t = this.__set__;
        return delete this.__set__, t
    }, b.getSize = function () {
        var t = this.canvas.parentNode;
        return {
            "width": t.offsetWidth,
            "height": t.offsetHeight
        }
    }, b.setSize = function (t, r) {
        return e._engine.setSize.call(this, t, r)
    }, b.setViewBox = function (t, r, i, n, a) {
        return e._engine.setViewBox.call(this, t, r, i, n, a)
    }, b.top = b.bottom = null, b.raphael = e;
    var Je = function (t) {
        var e = t.getBoundingClientRect(),
            r = t.ownerDocument,
            i = r.body,
            n = r.documentElement,
            a = n.clientTop || i.clientTop || 0,
            s = n.clientLeft || i.clientLeft || 0,
            o = e.top + (C.win.pageYOffset || n.scrollTop || i.scrollTop) - a,
            l = e.left + (C.win.pageXOffset || n.scrollLeft || i.scrollLeft) - s;
        return {
            "y": o,
            "x": l
        }
    };
    b.getElementByPoint = function (t, e) {
        var r = this,
            i = r.canvas,
            n = C.doc.elementFromPoint(t, e);
        if (C.win.opera && "svg" == n.tagName) {
            var a = Je(i),
                s = i.createSVGRect();
            s.x = t - a.x, s.y = e - a.y, s.width = s.height = 1;
            var o = i.getIntersectionList(s, null);
            o.length && (n = o[o.length - 1])
        }
        if (!n) return null;
        for (; n.parentNode && n != i.parentNode && !n.raphael;) n = n.parentNode;
        return n == r.canvas.parentNode && (n = i), n = n && n.raphael ? r.getById(n.raphaelid) : null
    }, b.getElementsByBBox = function (t) {
        var r = this.set();
        return this.forEach(function (i) {
            e.isBBoxIntersect(i.getBBox(), t) && r.push(i)
        }), r
    }, b.getById = function (t) {
        for (var e = this.bottom; e;) {
            if (e.id == t) return e;
            e = e.next
        }
        return null
    }, b.forEach = function (t, e) {
        for (var r = this.bottom; r;) {
            if (t.call(e, r) === !1) return this;
            r = r.next
        }
        return this
    }, b.getElementsByPoint = function (t, e) {
        var r = this.set();
        return this.forEach(function (i) {
            i.isPointInside(t, e) && r.push(i)
        }), r
    }, $e.isPointInside = function (t, r) {
        var i = this.realPath = de[this.type](this);
        return this.attr("transform") && this.attr("transform").length && (i = e.transformPath(i, this.attr("transform"))), e.isPointInsidePath(i, t, r)
    }, $e.getBBox = function (t) {
        if (this.removed) return {};
        var e = this._;
        return t ? ((e.dirty || !e.bboxwt) && (this.realPath = de[this.type](this), e.bboxwt = Ce(this.realPath), e.bboxwt.toString = d, e.dirty = 0), e.bboxwt) : ((e.dirty || e.dirtyT || !e.bbox) && ((e.dirty || !this.realPath) && (e.bboxwt = 0, this.realPath = de[this.type](this)), e.bbox = Ce(ge(this.realPath, this.matrix)), e.bbox.toString = d, e.dirty = e.dirtyT = 0), e.bbox)
    }, $e.clone = function () {
        if (this.removed) return null;
        var t = this.paper[this.type]().attr(this.attr());
        return this.__set__ && this.__set__.push(t), t
    }, $e.glow = function (t) {
        if ("text" == this.type) return null;
        t = t || {};
        var e = {
                "width": (t.width || 10) + (+this.attr("stroke-width") || 1),
                "fill": t.fill || !1,
                "opacity": null == t.opacity ? .5 : t.opacity,
                "offsetx": t.offsetx || 0,
                "offsety": t.offsety || 0,
                "color": t.color || "#000"
            },
            r = e.width / 2,
            i = this.paper,
            n = i.set(),
            a = this.realPath || de[this.type](this);
        a = this.matrix ? ge(a, this.matrix) : a;
        for (var s = 1; r + 1 > s; s++) n.push(i.path(a).attr({
            "stroke": e.color,
            "fill": e.fill ? e.color : "none",
            "stroke-linejoin": "round",
            "stroke-linecap": "round",
            "stroke-width": +(e.width / r * s).toFixed(3),
            "opacity": +(e.opacity / r).toFixed(3)
        }));
        return n.insertBefore(this).translate(e.offsetx, e.offsety)
    };
    var Ke = function (t, r, i, n, a, s, o, u, c) {
            return null == c ? l(t, r, i, n, a, s, o, u) : e.findDotsAtSegment(t, r, i, n, a, s, o, u, h(t, r, i, n, a, s, o, u, c))
        },
        tr = function (t, r) {
            return function (i, n, a) {
                i = Pe(i);
                for (var s, o, l, h, u, c = "", f = {}, p = 0, d = 0, g = i.length; g > d; d++) {
                    if (l = i[d], "M" == l[0]) s = +l[1], o = +l[2];
                    else {
                        if (h = Ke(s, o, l[1], l[2], l[3], l[4], l[5], l[6]), p + h > n) {
                            if (r && !f.start) {
                                if (u = Ke(s, o, l[1], l[2], l[3], l[4], l[5], l[6], n - p), c += ["C" + u.start.x, u.start.y, u.m.x, u.m.y, u.x, u.y], a) return c;
                                f.start = c, c = ["M" + u.x, u.y + "C" + u.n.x, u.n.y, u.end.x, u.end.y, l[5], l[6]].join(), p += h, s = +l[5], o = +l[6];
                                continue
                            }
                            if (!t && !r) return u = Ke(s, o, l[1], l[2], l[3], l[4], l[5], l[6], n - p), {
                                "x": u.x,
                                "y": u.y,
                                "alpha": u.alpha
                            }
                        }
                        p += h, s = +l[5], o = +l[6]
                    }
                    c += l.shift() + l
                }
                return f.end = c, u = t ? p : r ? f : e.findDotsAtSegment(s, o, l[0], l[1], l[2], l[3], l[4], l[5], 1), u.alpha && (u = {
                    "x": u.x,
                    "y": u.y,
                    "alpha": u.alpha
                }), u
            }
        },
        er = tr(1),
        rr = tr(),
        ir = tr(0, 1);
    e.getTotalLength = er, e.getPointAtLength = rr, e.getSubpath = function (t, e, r) {
        if (this.getTotalLength(t) - r < 1e-6) return ir(t, e).end;
        var i = ir(t, r, 1);
        return e ? ir(i, e).end : i
    }, $e.getTotalLength = function () {
        var t = this.getPath();
        return t ? this.node.getTotalLength ? this.node.getTotalLength() : er(t) : void 0
    }, $e.getPointAtLength = function (t) {
        var e = this.getPath();
        return e ? rr(e, t) : void 0
    }, $e.getPath = function () {
        var t, r = e._getPath[this.type];
        return "text" != this.type && "set" != this.type ? (r && (t = r(this)), t) : void 0
    }, $e.getSubpath = function (t, r) {
        var i = this.getPath();
        return i ? e.getSubpath(i, t, r) : void 0
    };
    var nr = e.easing_formulas = {
        "linear": function (t) {
            return t
        },
        "<": function (t) {
            return O(t, 1.7)
        },
        ">": function (t) {
            return O(t, .48)
        },
        "<>": function (t) {
            var e = .48 - t / 1.04,
                r = I.sqrt(.1734 + e * e),
                i = r - e,
                n = O(V(i), 1 / 3) * (0 > i ? -1 : 1),
                a = -r - e,
                s = O(V(a), 1 / 3) * (0 > a ? -1 : 1),
                o = n + s + .5;
            return 3 * (1 - o) * o * o + o * o * o
        },
        "backIn": function (t) {
            var e = 1.70158;
            return t * t * ((e + 1) * t - e)
        },
        "backOut": function (t) {
            t -= 1;
            var e = 1.70158;
            return t * t * ((e + 1) * t + e) + 1
        },
        "elastic": function (t) {
            return t == !!t ? t : O(2, -10 * t) * I.sin(2 * (t - .075) * Y / .3) + 1
        },
        "bounce": function (t) {
            var e, r = 7.5625,
                i = 2.75;
            return 1 / i > t ? e = r * t * t : 2 / i > t ? (t -= 1.5 / i, e = r * t * t + .75) : 2.5 / i > t ? (t -= 2.25 / i, e = r * t * t + .9375) : (t -= 2.625 / i, e = r * t * t + .984375), e
        }
    };
    nr.easeIn = nr["ease-in"] = nr["<"], nr.easeOut = nr["ease-out"] = nr[">"], nr.easeInOut = nr["ease-in-out"] = nr["<>"], nr["back-in"] = nr.backIn, nr["back-out"] = nr.backOut;
    var ar = [],
        sr = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function (t) {
            setTimeout(t, 16)
        },
        or = function () {
            for (var r = +new Date, i = 0; i < ar.length; i++) {
                var n = ar[i];
                if (!n.el.removed && !n.paused) {
                    var a, s, o = r - n.start,
                        l = n.ms,
                        h = n.easing,
                        u = n.from,
                        c = n.diff,
                        f = n.to,
                        p = (n.t, n.el),
                        d = {},
                        g = {};
                    if (n.initstatus ? (o = (n.initstatus * n.anim.top - n.prev) / (n.percent - n.prev) * l, n.status = n.initstatus, delete n.initstatus, n.stop && ar.splice(i--, 1)) : n.status = (n.prev + (n.percent - n.prev) * (o / l)) / n.anim.top, !(0 > o))
                        if (l > o) {
                            var x = h(o / l);
                            for (var y in u)
                                if (u[B](y)) {
                                    switch (re[y]) {
                                        case W:
                                            a = +u[y] + x * l * c[y];
                                            break;
                                        case "colour":
                                            a = "rgb(" + [lr(Q(u[y].r + x * l * c[y].r)), lr(Q(u[y].g + x * l * c[y].g)), lr(Q(u[y].b + x * l * c[y].b))].join(",") + ")";
                                            break;
                                        case "path":
                                            a = [];
                                            for (var m = 0, b = u[y].length; b > m; m++) {
                                                a[m] = [u[y][m][0]];
                                                for (var _ = 1, w = u[y][m].length; w > _; _++) a[m][_] = +u[y][m][_] + x * l * c[y][m][_];
                                                a[m] = a[m].join(L)
                                            }
                                            a = a.join(L);
                                            break;
                                        case "transform":
                                            if (c[y].real)
                                                for (a = [], m = 0, b = u[y].length; b > m; m++)
                                                    for (a[m] = [u[y][m][0]], _ = 1, w = u[y][m].length; w > _; _++) a[m][_] = u[y][m][_] + x * l * c[y][m][_];
                                            else {
                                                var k = function (t) {
                                                    return +u[y][t] + x * l * c[y][t]
                                                };
                                                a = [
                                                    ["m", k(0), k(1), k(2), k(3), k(4), k(5)]
                                                ]
                                            }
                                            break;
                                        case "csv":
                                            if ("clip-rect" == y)
                                                for (a = [], m = 4; m--;) a[m] = +u[y][m] + x * l * c[y][m];
                                            break;
                                        default:
                                            var C = [][E](u[y]);
                                            for (a = [], m = p.paper.customAttributes[y].length; m--;) a[m] = +C[m] + x * l * c[y][m]
                                    }
                                    d[y] = a
                                }
                            p.attr(d),
                                function (e, r, i) {
                                    setTimeout(function () {
                                        t("raphael.anim.frame." + e, r, i)
                                    })
                                }(p.id, p, n.anim)
                        } else {
                            if (function (r, i, n) {
                                    setTimeout(function () {
                                        t("raphael.anim.frame." + i.id, i, n), t("raphael.anim.finish." + i.id, i, n), e.is(r, "function") && r.call(i)
                                    })
                                }(n.callback, p, n.anim), p.attr(f), ar.splice(i--, 1), n.repeat > 1 && !n.next) {
                                for (s in f) f[B](s) && (g[s] = n.totalOrigin[s]);
                                n.el.attr(g), v(n.anim, n.el, n.anim.percents[0], null, n.totalOrigin, n.repeat - 1)
                            }
                            n.next && !n.stop && v(n.anim, n.el, n.next, null, n.totalOrigin, n.repeat)
                        }
                }
            }
            ar.length && sr(or)
        },
        lr = function (t) {
            return t > 255 ? 255 : 0 > t ? 0 : t
        };
    $e.animateWith = function (t, r, i, n, a, s) {
        var o = this;
        if (o.removed) return s && s.call(o), o;
        var l = i instanceof x ? i : e.animation(i, n, a, s);
        v(l, o, l.percents[0], null, o.attr());
        for (var h = 0, u = ar.length; u > h; h++)
            if (ar[h].anim == r && ar[h].el == t) {
                ar[u - 1].start = ar[h].start;
                break
            }
        return o
    }, $e.onAnimation = function (e) {
        return e ? t.on("raphael.anim.frame." + this.id, e) : t.unbind("raphael.anim.frame." + this.id), this
    }, x.prototype.delay = function (t) {
        var e = new x(this.anim, this.ms);
        return e.times = this.times, e.del = +t || 0, e
    }, x.prototype.repeat = function (t) {
        var e = new x(this.anim, this.ms);
        return e.del = this.del, e.times = I.floor(q(t, 0)) || 1, e
    }, e.animation = function (t, r, i, n) {
        if (t instanceof x) return t;
        (e.is(i, "function") || !i) && (n = n || i || null, i = null), t = Object(t), r = +r || 0;
        var a, s, o = {};
        for (s in t) t[B](s) && J(s) != s && J(s) + "%" != s && (a = !0, o[s] = t[s]);
        if (a) return i && (o.easing = i), n && (o.callback = n), new x({
            "100": o
        }, r);
        if (n) {
            var l = 0;
            for (var h in t) {
                var u = K(h);
                t[B](h) && u > l && (l = u)
            }
            l += "%", !t[l].callback && (t[l].callback = n)
        }
        return new x(t, r)
    }, $e.animate = function (t, r, i, n) {
        var a = this;
        if (a.removed) return n && n.call(a), a;
        var s = t instanceof x ? t : e.animation(t, r, i, n);
        return v(s, a, s.percents[0], null, a.attr()), a
    }, $e.setTime = function (t, e) {
        return t && null != e && this.status(t, D(e, t.ms) / t.ms), this
    }, $e.status = function (t, e) {
        var r, i, n = [],
            a = 0;
        if (null != e) return v(t, this, -1, D(e, 1)), this;
        for (r = ar.length; r > a; a++)
            if (i = ar[a], i.el.id == this.id && (!t || i.anim == t)) {
                if (t) return i.status;
                n.push({
                    "anim": i.anim,
                    "status": i.status
                })
            }
        return t ? 0 : n
    }, $e.pause = function (e) {
        for (var r = 0; r < ar.length; r++) ar[r].el.id != this.id || e && ar[r].anim != e || t("raphael.anim.pause." + this.id, this, ar[r].anim) !== !1 && (ar[r].paused = !0);
        return this
    }, $e.resume = function (e) {
        for (var r = 0; r < ar.length; r++)
            if (ar[r].el.id == this.id && (!e || ar[r].anim == e)) {
                var i = ar[r];
                t("raphael.anim.resume." + this.id, this, i.anim) !== !1 && (delete i.paused, this.status(i.anim, i.status))
            }
        return this
    }, $e.stop = function (e) {
        for (var r = 0; r < ar.length; r++) ar[r].el.id != this.id || e && ar[r].anim != e || t("raphael.anim.stop." + this.id, this, ar[r].anim) !== !1 && ar.splice(r--, 1);
        return this
    }, t.on("raphael.remove", y), t.on("raphael.clear", y), $e.toString = function () {
        return "Rapha\xebl\u2019s object"
    };
    var hr = function (t) {
            if (this.items = [], this.length = 0, this.type = "set", t)
                for (var e = 0, r = t.length; r > e; e++) !t[e] || t[e].constructor != $e.constructor && t[e].constructor != hr || (this[this.items.length] = this.items[this.items.length] = t[e], this.length++)
        },
        ur = hr.prototype;
    ur.push = function () {
        for (var t, e, r = 0, i = arguments.length; i > r; r++) t = arguments[r], !t || t.constructor != $e.constructor && t.constructor != hr || (e = this.items.length, this[e] = this.items[e] = t, this.length++);
        return this
    }, ur.pop = function () {
        return this.length && delete this[this.length--], this.items.pop()
    }, ur.forEach = function (t, e) {
        for (var r = 0, i = this.items.length; i > r; r++)
            if (t.call(e, this.items[r], r) === !1) return this;
        return this
    };
    for (var cr in $e) $e[B](cr) && (ur[cr] = function (t) {
        return function () {
            var e = arguments;
            return this.forEach(function (r) {
                r[t][A](r, e)
            })
        }
    }(cr));
    return ur.attr = function (t, r) {
            if (t && e.is(t, H) && e.is(t[0], "object"))
                for (var i = 0, n = t.length; n > i; i++) this.items[i].attr(t[i]);
            else
                for (var a = 0, s = this.items.length; s > a; a++) this.items[a].attr(t, r);
            return this
        }, ur.clear = function () {
            for (; this.length;) this.pop()
        }, ur.splice = function (t, e) {
            t = 0 > t ? q(this.length + t, 0) : t, e = q(0, D(this.length - t, e));
            var r, i = [],
                n = [],
                a = [];
            for (r = 2; r < arguments.length; r++) a.push(arguments[r]);
            for (r = 0; e > r; r++) n.push(this[t + r]);
            for (; r < this.length - t; r++) i.push(this[t + r]);
            var s = a.length;
            for (r = 0; r < s + i.length; r++) this.items[t + r] = this[t + r] = s > r ? a[r] : i[r - s];
            for (r = this.items.length = this.length -= e - s; this[r];) delete this[r++];
            return new hr(n)
        }, ur.exclude = function (t) {
            for (var e = 0, r = this.length; r > e; e++)
                if (this[e] == t) return this.splice(e, 1), !0
        }, ur.animate = function (t, r, i, n) {
            (e.is(i, "function") || !i) && (n = i || null);
            var a, s, o = this.items.length,
                l = o,
                h = this;
            if (!o) return this;
            n && (s = function () {
                !--o && n.call(h)
            }), i = e.is(i, G) ? i : s;
            var u = e.animation(t, r, i, s);
            for (a = this.items[--l].animate(u); l--;) this.items[l] && !this.items[l].removed && this.items[l].animateWith(a, u, u), this.items[l] && !this.items[l].removed || o--;
            return this
        }, ur.insertAfter = function (t) {
            for (var e = this.items.length; e--;) this.items[e].insertAfter(t);
            return this
        }, ur.getBBox = function () {
            for (var t = [], e = [], r = [], i = [], n = this.items.length; n--;)
                if (!this.items[n].removed) {
                    var a = this.items[n].getBBox();
                    t.push(a.x), e.push(a.y), r.push(a.x + a.width), i.push(a.y + a.height)
                }
            return t = D[A](0, t), e = D[A](0, e), r = q[A](0, r), i = q[A](0, i), {
                "x": t,
                "y": e,
                "x2": r,
                "y2": i,
                "width": r - t,
                "height": i - e
            }
        }, ur.clone = function (t) {
            t = this.paper.set();
            for (var e = 0, r = this.items.length; r > e; e++) t.push(this.items[e].clone());
            return t
        }, ur.toString = function () {
            return "Rapha\xebl\u2018s set"
        }, ur.glow = function (t) {
            var e = this.paper.set();
            return this.forEach(function (r) {
                var i = r.glow(t);
                null != i && i.forEach(function (t) {
                    e.push(t)
                })
            }), e
        }, ur.isPointInside = function (t, e) {
            var r = !1;
            return this.forEach(function (i) {
                return i.isPointInside(t, e) ? (r = !0, !1) : void 0
            }), r
        }, e.registerFont = function (t) {
            if (!t.face) return t;
            this.fonts = this.fonts || {};
            var e = {
                    "w": t.w,
                    "face": {},
                    "glyphs": {}
                },
                r = t.face["font-family"];
            for (var i in t.face) t.face[B](i) && (e.face[i] = t.face[i]);
            if (this.fonts[r] ? this.fonts[r].push(e) : this.fonts[r] = [e], !t.svg) {
                e.face["units-per-em"] = K(t.face["units-per-em"], 10);
                for (var n in t.glyphs)
                    if (t.glyphs[B](n)) {
                        var a = t.glyphs[n];
                        if (e.glyphs[n] = {
                                "w": a.w,
                                "k": {},
                                "d": a.d && "M" + a.d.replace(/[mlcxtrv]/g, function (t) {
                                    return {
                                        "l": "L",
                                        "c": "C",
                                        "x": "z",
                                        "t": "m",
                                        "r": "l",
                                        "v": "c"
                                    }[t] || "M"
                                }) + "z"
                            }, a.k)
                            for (var s in a.k) a[B](s) && (e.glyphs[n].k[s] = a.k[s])
                    }
            }
            return t
        }, b.getFont = function (t, r, i, n) {
            if (n = n || "normal", i = i || "normal", r = +r || {
                    "normal": 400,
                    "bold": 700,
                    "lighter": 300,
                    "bolder": 800
                }[r] || 400, e.fonts) {
                var a = e.fonts[t];
                if (!a) {
                    var s = new RegExp("(^|\\s)" + t.replace(/[^\w\d\s+!~.:_-]/g, M) + "(\\s|$)", "i");
                    for (var o in e.fonts)
                        if (e.fonts[B](o) && s.test(o)) {
                            a = e.fonts[o];
                            break
                        }
                }
                var l;
                if (a)
                    for (var h = 0, u = a.length; u > h && (l = a[h], l.face["font-weight"] != r || l.face["font-style"] != i && l.face["font-style"] || l.face["font-stretch"] != n); h++);
                return l
            }
        }, b.print = function (t, r, i, n, a, s, o, l) {
            s = s || "middle", o = q(D(o || 0, 1), -1), l = q(D(l || 1, 3), 1);
            var h, u = z(i)[P](M),
                c = 0,
                f = 0,
                p = M;
            if (e.is(n, "string") && (n = this.getFont(n)), n) {
                h = (a || 16) / n.face["units-per-em"];
                for (var d = n.face.bbox[P](_), g = +d[0], x = d[3] - d[1], v = 0, y = +d[1] + ("baseline" == s ? x + +n.face.descent : x / 2), m = 0, b = u.length; b > m; m++) {
                    if ("\n" == u[m]) c = 0, k = 0, f = 0, v += x * l;
                    else {
                        var w = f && n.glyphs[u[m - 1]] || {},
                            k = n.glyphs[u[m]];
                        c += f ? (w.w || n.w) + (w.k && w.k[u[m]] || 0) + n.w * o : 0, f = 1
                    }
                    k && k.d && (p += e.transformPath(k.d, ["t", c * h, v * h, "s", h, h, g, y, "t", (t - g) / h, (r - y) / h]))
                }
            }
            return this.path(p).attr({
                "fill": "#000",
                "stroke": "none"
            })
        }, b.add = function (t) {
            if (e.is(t, "array"))
                for (var r, i = this.set(), n = 0, a = t.length; a > n; n++) r = t[n] || {}, w[B](r.type) && i.push(this[r.type]().attr(r));
            return i
        }, e.format = function (t, r) {
            var i = e.is(r, H) ? [0][E](r) : arguments;
            return t && e.is(t, G) && i.length - 1 && (t = t.replace(k, function (t, e) {
                return null == i[++e] ? M : i[e]
            })), t || M
        }, e.fullfill = function () {
            var t = /\{([^\}]+)\}/g,
                e = /(?:(?:^|\.)(.+?)(?=\[|\.|$|\()|\[('|")(.+?)\2\])(\(\))?/g,
                r = function (t, r, i) {
                    var n = i;
                    return r.replace(e, function (t, e, r, i, a) {
                        e = e || i, n && (e in n && (n = n[e]), "function" == typeof n && a && (n = n()))
                    }), n = (null == n || n == i ? t : n) + ""
                };
            return function (e, i) {
                return String(e).replace(t, function (t, e) {
                    return r(t, e, i)
                })
            }
        }(), e.ninja = function () {
            if (S.was) C.win.Raphael = S.is;
            else {
                window.Raphael = void 0;
                try {
                    delete window.Raphael
                } catch (t) {}
            }
            return e
        }, e.st = ur, t.on("raphael.DOMload", function () {
            m = !0
        }),
        function (t, r, i) {
            function n() {
                /in/.test(t.readyState) ? setTimeout(n, 9) : e.eve("raphael.DOMload")
            }
            null == t.readyState && t.addEventListener && (t.addEventListener(r, i = function () {
                t.removeEventListener(r, i, !1), t.readyState = "complete"
            }, !1), t.readyState = "loading"), n()
        }(document, "DOMContentLoaded"), e
}),
function (t, e) {
    "function" == typeof define && define.amd ? define("raphael.svg", ["raphael.core"], function (t) {
        return e(t)
    }) : e("object" == typeof exports ? require("./raphael.core") : t.Raphael)
}(this, function (t) {
    if (!t || t.svg) {
        var e = "hasOwnProperty",
            r = String,
            i = parseFloat,
            n = parseInt,
            a = Math,
            s = a.max,
            o = a.abs,
            l = a.pow,
            h = /[, ]+/,
            u = t.eve,
            c = "",
            f = " ",
            p = "http://www.w3.org/1999/xlink",
            d = {
                "block": "M5,0 0,2.5 5,5z",
                "classic": "M5,0 0,2.5 5,5 3.5,3 3.5,2z",
                "diamond": "M2.5,0 5,2.5 2.5,5 0,2.5z",
                "open": "M6,1 1,3.5 6,6",
                "oval": "M2.5,0A2.5,2.5,0,0,1,2.5,5 2.5,2.5,0,0,1,2.5,0z"
            },
            g = {};
        t.toString = function () {
            return "Your browser supports SVG.\nYou are running Rapha\xebl " + this.version
        };
        var x = function (i, n) {
                if (n) {
                    "string" == typeof i && (i = x(i));
                    for (var a in n) n[e](a) && ("xlink:" == a.substring(0, 6) ? i.setAttributeNS(p, a.substring(6), r(n[a])) : i.setAttribute(a, r(n[a])))
                } else i = t._g.doc.createElementNS("http://www.w3.org/2000/svg", i), i.style && (i.style.webkitTapHighlightColor = "rgba(0,0,0,0)");
                return i
            },
            v = function (e, n) {
                var h = "linear",
                    u = e.id + n,
                    f = .5,
                    p = .5,
                    d = e.node,
                    g = e.paper,
                    v = d.style,
                    y = t._g.doc.getElementById(u);
                if (!y) {
                    if (n = r(n).replace(t._radial_gradient, function (t, e, r) {
                            if (h = "radial", e && r) {
                                f = i(e), p = i(r);
                                var n = 2 * (p > .5) - 1;
                                l(f - .5, 2) + l(p - .5, 2) > .25 && (p = a.sqrt(.25 - l(f - .5, 2)) * n + .5) && .5 != p && (p = p.toFixed(5) - 1e-5 * n)
                            }
                            return c
                        }), n = n.split(/\s*\-\s*/), "linear" == h) {
                        var b = n.shift();
                        if (b = -i(b), isNaN(b)) return null;
                        var _ = [0, 0, a.cos(t.rad(b)), a.sin(t.rad(b))],
                            w = 1 / (s(o(_[2]), o(_[3])) || 1);
                        _[2] *= w, _[3] *= w, _[2] < 0 && (_[0] = -_[2], _[2] = 0), _[3] < 0 && (_[1] = -_[3], _[3] = 0)
                    }
                    var k = t._parseDots(n);
                    if (!k) return null;
                    if (u = u.replace(/[\(\)\s,\xb0#]/g, "_"), e.gradient && u != e.gradient.id && (g.defs.removeChild(e.gradient), delete e.gradient), !e.gradient) {
                        y = x(h + "Gradient", {
                            "id": u
                        }), e.gradient = y, x(y, "radial" == h ? {
                            "fx": f,
                            "fy": p
                        } : {
                            "x1": _[0],
                            "y1": _[1],
                            "x2": _[2],
                            "y2": _[3],
                            "gradientTransform": e.matrix.invert()
                        }), g.defs.appendChild(y);
                        for (var B = 0, C = k.length; C > B; B++) y.appendChild(x("stop", {
                            "offset": k[B].offset ? k[B].offset : B ? "100%" : "0%",
                            "stop-color": k[B].color || "#fff",
                            "stop-opacity": isFinite(k[B].opacity) ? k[B].opacity : 1
                        }))
                    }
                }
                return x(d, {
                    "fill": m(u),
                    "opacity": 1,
                    "fill-opacity": 1
                }), v.fill = c, v.opacity = 1, v.fillOpacity = 1, 1
            },
            y = function () {
                var t = document.documentMode;
                return t && (9 === t || 10 === t)
            },
            m = function (t) {
                if (y()) return "url('#" + t + "')";
                var e = document.location,
                    r = e.protocol + "//" + e.host + e.pathname + e.search;
                return "url('" + r + "#" + t + "')"
            },
            b = function (t) {
                var e = t.getBBox(1);
                x(t.pattern, {
                    "patternTransform": t.matrix.invert() + " translate(" + e.x + "," + e.y + ")"
                })
            },
            _ = function (i, n, a) {
                if ("path" == i.type) {
                    for (var s, o, l, h, u, f = r(n).toLowerCase().split("-"), p = i.paper, v = a ? "end" : "start", y = i.node, m = i.attrs, b = m["stroke-width"], _ = f.length, w = "classic", k = 3, B = 3, C = 5; _--;) switch (f[_]) {
                        case "block":
                        case "classic":
                        case "oval":
                        case "diamond":
                        case "open":
                        case "none":
                            w = f[_];
                            break;
                        case "wide":
                            B = 5;
                            break;
                        case "narrow":
                            B = 2;
                            break;
                        case "long":
                            k = 5;
                            break;
                        case "short":
                            k = 2
                    }
                    if ("open" == w ? (k += 2, B += 2, C += 2, l = 1, h = a ? 4 : 1, u = {
                            "fill": "none",
                            "stroke": m.stroke
                        }) : (h = l = k / 2, u = {
                            "fill": m.stroke,
                            "stroke": "none"
                        }), i._.arrows ? a ? (i._.arrows.endPath && g[i._.arrows.endPath]--, i._.arrows.endMarker && g[i._.arrows.endMarker]--) : (i._.arrows.startPath && g[i._.arrows.startPath]--, i._.arrows.startMarker && g[i._.arrows.startMarker]--) : i._.arrows = {}, "none" != w) {
                        var S = "raphael-marker-" + w,
                            T = "raphael-marker-" + v + w + k + B + "-obj" + i.id;
                        t._g.doc.getElementById(S) ? g[S]++ : (p.defs.appendChild(x(x("path"), {
                            "stroke-linecap": "round",
                            "d": d[w],
                            "id": S
                        })), g[S] = 1);
                        var A, E = t._g.doc.getElementById(T);
                        E ? (g[T]++, A = E.getElementsByTagName("use")[0]) : (E = x(x("marker"), {
                            "id": T,
                            "markerHeight": B,
                            "markerWidth": k,
                            "orient": "auto",
                            "refX": h,
                            "refY": B / 2
                        }), A = x(x("use"), {
                            "xlink:href": "#" + S,
                            "transform": (a ? "rotate(180 " + k / 2 + " " + B / 2 + ") " : c) + "scale(" + k / C + "," + B / C + ")",
                            "stroke-width": (1 / ((k / C + B / C) / 2)).toFixed(4)
                        }), E.appendChild(A), p.defs.appendChild(E), g[T] = 1), x(A, u);
                        var N = l * ("diamond" != w && "oval" != w);
                        a ? (s = i._.arrows.startdx * b || 0, o = t.getTotalLength(m.path) - N * b) : (s = N * b, o = t.getTotalLength(m.path) - (i._.arrows.enddx * b || 0)), u = {}, u["marker-" + v] = "url(#" + T + ")", (o || s) && (u.d = t.getSubpath(m.path, s, o)), x(y, u), i._.arrows[v + "Path"] = S, i._.arrows[v + "Marker"] = T, i._.arrows[v + "dx"] = N, i._.arrows[v + "Type"] = w, i._.arrows[v + "String"] = n
                    } else a ? (s = i._.arrows.startdx * b || 0, o = t.getTotalLength(m.path) - s) : (s = 0, o = t.getTotalLength(m.path) - (i._.arrows.enddx * b || 0)), i._.arrows[v + "Path"] && x(y, {
                        "d": t.getSubpath(m.path, s, o)
                    }), delete i._.arrows[v + "Path"], delete i._.arrows[v + "Marker"], delete i._.arrows[v + "dx"], delete i._.arrows[v + "Type"], delete i._.arrows[v + "String"];
                    for (u in g)
                        if (g[e](u) && !g[u]) {
                            var M = t._g.doc.getElementById(u);
                            M && M.parentNode.removeChild(M)
                        }
                }
            },
            w = {
                "-": [3, 1],
                ".": [1, 1],
                "-.": [3, 1, 1, 1],
                "-..": [3, 1, 1, 1, 1, 1],
                ". ": [1, 3],
                "- ": [4, 3],
                "--": [8, 3],
                "- .": [4, 3, 1, 3],
                "--.": [8, 3, 1, 3],
                "--..": [8, 3, 1, 3, 1, 3]
            },
            k = function (t, e, i) {
                if (e = w[r(e).toLowerCase()]) {
                    for (var n = t.attrs["stroke-width"] || "1", a = {
                            "round": n,
                            "square": n,
                            "butt": 0
                        }[t.attrs["stroke-linecap"] || i["stroke-linecap"]] || 0, s = [], o = e.length; o--;) s[o] = e[o] * n + (o % 2 ? 1 : -1) * a;
                    x(t.node, {
                        "stroke-dasharray": s.join(",")
                    })
                } else x(t.node, {
                    "stroke-dasharray": "none"
                })
            },
            B = function (i, a) {
                var l = i.node,
                    u = i.attrs,
                    f = l.style.visibility;
                l.style.visibility = "hidden";
                for (var d in a)
                    if (a[e](d)) {
                        if (!t._availableAttrs[e](d)) continue;
                        var g = a[d];
                        switch (u[d] = g, d) {
                            case "blur":
                                i.blur(g);
                                break;
                            case "title":
                                var y = l.getElementsByTagName("title");
                                if (y.length && (y = y[0])) y.firstChild.nodeValue = g;
                                else {
                                    y = x("title");
                                    var m = t._g.doc.createTextNode(g);
                                    y.appendChild(m), l.appendChild(y)
                                }
                                break;
                            case "href":
                            case "target":
                                var w = l.parentNode;
                                if ("a" != w.tagName.toLowerCase()) {
                                    var B = x("a");
                                    w.insertBefore(B, l), B.appendChild(l), w = B
                                }
                                "target" == d ? w.setAttributeNS(p, "show", "blank" == g ? "new" : g) : w.setAttributeNS(p, d, g);
                                break;
                            case "cursor":
                                l.style.cursor = g;
                                break;
                            case "transform":
                                i.transform(g);
                                break;
                            case "arrow-start":
                                _(i, g);
                                break;
                            case "arrow-end":
                                _(i, g, 1);
                                break;
                            case "clip-rect":
                                var C = r(g).split(h);
                                if (4 == C.length) {
                                    i.clip && i.clip.parentNode.parentNode.removeChild(i.clip.parentNode);
                                    var T = x("clipPath"),
                                        A = x("rect");
                                    T.id = t.createUUID(), x(A, {
                                        "x": C[0],
                                        "y": C[1],
                                        "width": C[2],
                                        "height": C[3]
                                    }), T.appendChild(A), i.paper.defs.appendChild(T), x(l, {
                                        "clip-path": "url(#" + T.id + ")"
                                    }), i.clip = A
                                }
                                if (!g) {
                                    var E = l.getAttribute("clip-path");
                                    if (E) {
                                        var N = t._g.doc.getElementById(E.replace(/(^url\(#|\)$)/g, c));
                                        N && N.parentNode.removeChild(N), x(l, {
                                            "clip-path": c
                                        }), delete i.clip
                                    }
                                }
                                break;
                            case "path":
                                "path" == i.type && (x(l, {
                                    "d": g ? u.path = t._pathToAbsolute(g) : "M0,0"
                                }), i._.dirty = 1, i._.arrows && ("startString" in i._.arrows && _(i, i._.arrows.startString), "endString" in i._.arrows && _(i, i._.arrows.endString, 1)));
                                break;
                            case "width":
                                if (l.setAttribute(d, g), i._.dirty = 1, !u.fx) break;
                                d = "x", g = u.x;
                            case "x":
                                u.fx && (g = -u.x - (u.width || 0));
                            case "rx":
                                if ("rx" == d && "rect" == i.type) break;
                            case "cx":
                                l.setAttribute(d, g), i.pattern && b(i), i._.dirty = 1;
                                break;
                            case "height":
                                if (l.setAttribute(d, g), i._.dirty = 1, !u.fy) break;
                                d = "y", g = u.y;
                            case "y":
                                u.fy && (g = -u.y - (u.height || 0));
                            case "ry":
                                if ("ry" == d && "rect" == i.type) break;
                            case "cy":
                                l.setAttribute(d, g), i.pattern && b(i), i._.dirty = 1;
                                break;
                            case "r":
                                "rect" == i.type ? x(l, {
                                    "rx": g,
                                    "ry": g
                                }) : l.setAttribute(d, g), i._.dirty = 1;
                                break;
                            case "src":
                                "image" == i.type && l.setAttributeNS(p, "href", g);
                                break;
                            case "stroke-width":
                                (1 != i._.sx || 1 != i._.sy) && (g /= s(o(i._.sx), o(i._.sy)) || 1), l.setAttribute(d, g), u["stroke-dasharray"] && k(i, u["stroke-dasharray"], a), i._.arrows && ("startString" in i._.arrows && _(i, i._.arrows.startString), "endString" in i._.arrows && _(i, i._.arrows.endString, 1));
                                break;
                            case "stroke-dasharray":
                                k(i, g, a);
                                break;
                            case "fill":
                                var M = r(g).match(t._ISURL);
                                if (M) {
                                    T = x("pattern");
                                    var L = x("image");
                                    T.id = t.createUUID(), x(T, {
                                            "x": 0,
                                            "y": 0,
                                            "patternUnits": "userSpaceOnUse",
                                            "height": 1,
                                            "width": 1
                                        }), x(L, {
                                            "x": 0,
                                            "y": 0,
                                            "xlink:href": M[1]
                                        }), T.appendChild(L),
                                        function (e) {
                                            t._preload(M[1], function () {
                                                var t = this.offsetWidth,
                                                    r = this.offsetHeight;
                                                x(e, {
                                                    "width": t,
                                                    "height": r
                                                }), x(L, {
                                                    "width": t,
                                                    "height": r
                                                })
                                            })
                                        }(T), i.paper.defs.appendChild(T), x(l, {
                                            "fill": "url(#" + T.id + ")"
                                        }), i.pattern = T, i.pattern && b(i);
                                    break
                                }
                                var z = t.getRGB(g);
                                if (z.error) {
                                    if (("circle" == i.type || "ellipse" == i.type || "r" != r(g).charAt()) && v(i, g)) {
                                        if ("opacity" in u || "fill-opacity" in u) {
                                            var P = t._g.doc.getElementById(l.getAttribute("fill").replace(/^url\(#|\)$/g, c));
                                            if (P) {
                                                var F = P.getElementsByTagName("stop");
                                                x(F[F.length - 1], {
                                                    "stop-opacity": ("opacity" in u ? u.opacity : 1) * ("fill-opacity" in u ? u["fill-opacity"] : 1)
                                                })
                                            }
                                        }
                                        u.gradient = g, u.fill = "none";
                                        break
                                    }
                                } else delete a.gradient, delete u.gradient, !t.is(u.opacity, "undefined") && t.is(a.opacity, "undefined") && x(l, {
                                    "opacity": u.opacity
                                }), !t.is(u["fill-opacity"], "undefined") && t.is(a["fill-opacity"], "undefined") && x(l, {
                                    "fill-opacity": u["fill-opacity"]
                                });
                                z[e]("opacity") && x(l, {
                                    "fill-opacity": z.opacity > 1 ? z.opacity / 100 : z.opacity
                                });
                            case "stroke":
                                z = t.getRGB(g), l.setAttribute(d, z.hex), "stroke" == d && z[e]("opacity") && x(l, {
                                    "stroke-opacity": z.opacity > 1 ? z.opacity / 100 : z.opacity
                                }), "stroke" == d && i._.arrows && ("startString" in i._.arrows && _(i, i._.arrows.startString), "endString" in i._.arrows && _(i, i._.arrows.endString, 1));
                                break;
                            case "gradient":
                                ("circle" == i.type || "ellipse" == i.type || "r" != r(g).charAt()) && v(i, g);
                                break;
                            case "opacity":
                                u.gradient && !u[e]("stroke-opacity") && x(l, {
                                    "stroke-opacity": g > 1 ? g / 100 : g
                                });
                            case "fill-opacity":
                                if (u.gradient) {
                                    P = t._g.doc.getElementById(l.getAttribute("fill").replace(/^url\(#|\)$/g, c)), P && (F = P.getElementsByTagName("stop"), x(F[F.length - 1], {
                                        "stop-opacity": g
                                    }));
                                    break
                                }
                            default:
                                "font-size" == d && (g = n(g, 10) + "px");
                                var R = d.replace(/(\-.)/g, function (t) {
                                    return t.substring(1).toUpperCase()
                                });
                                l.style[R] = g, i._.dirty = 1, l.setAttribute(d, g)
                        }
                    }
                S(i, a), l.style.visibility = f
            },
            C = 1.2,
            S = function (i, a) {
                if ("text" == i.type && (a[e]("text") || a[e]("font") || a[e]("font-size") || a[e]("x") || a[e]("y"))) {
                    var s = i.attrs,
                        o = i.node,
                        l = o.firstChild ? n(t._g.doc.defaultView.getComputedStyle(o.firstChild, c).getPropertyValue("font-size"), 10) : 10;
                    if (a[e]("text")) {
                        for (s.text = a.text; o.firstChild;) o.removeChild(o.firstChild);
                        for (var h, u = r(a.text).split("\n"), f = [], p = 0, d = u.length; d > p; p++) h = x("tspan"), p && x(h, {
                            "dy": l * C,
                            "x": s.x
                        }), h.appendChild(t._g.doc.createTextNode(u[p])), o.appendChild(h), f[p] = h
                    } else
                        for (f = o.getElementsByTagName("tspan"), p = 0, d = f.length; d > p; p++) p ? x(f[p], {
                            "dy": l * C,
                            "x": s.x
                        }) : x(f[0], {
                            "dy": 0
                        });
                    x(o, {
                        "x": s.x,
                        "y": s.y
                    }), i._.dirty = 1;
                    var g = i._getBBox(),
                        v = s.y - (g.y + g.height / 2);
                    v && t.is(v, "finite") && x(f[0], {
                        "dy": v
                    })
                }
            },
            T = function (t) {
                return t.parentNode && "a" === t.parentNode.tagName.toLowerCase() ? t.parentNode : t
            },
            A = function (e, r) {
                this[0] = this.node = e, e.raphael = !0, this.id = t._oid++, e.raphaelid = this.id, this.matrix = t.matrix(), this.realPath = null, this.paper = r, this.attrs = this.attrs || {}, this._ = {
                    "transform": [],
                    "sx": 1,
                    "sy": 1,
                    "deg": 0,
                    "dx": 0,
                    "dy": 0,
                    "dirty": 1
                }, !r.bottom && (r.bottom = this), this.prev = r.top, r.top && (r.top.next = this), r.top = this, this.next = null
            },
            E = t.el;
        A.prototype = E, E.constructor = A, t._engine.path = function (t, e) {
            var r = x("path");
            e.canvas && e.canvas.appendChild(r);
            var i = new A(r, e);
            return i.type = "path", B(i, {
                "fill": "none",
                "stroke": "#000",
                "path": t
            }), i
        }, E.rotate = function (t, e, n) {
            if (this.removed) return this;
            if (t = r(t).split(h), t.length - 1 && (e = i(t[1]), n = i(t[2])), t = i(t[0]), null == n && (e = n), null == e || null == n) {
                var a = this.getBBox(1);
                e = a.x + a.width / 2, n = a.y + a.height / 2
            }
            return this.transform(this._.transform.concat([
                ["r", t, e, n]
            ])), this
        }, E.scale = function (t, e, n, a) {
            if (this.removed) return this;
            if (t = r(t).split(h), t.length - 1 && (e = i(t[1]), n = i(t[2]), a = i(t[3])), t = i(t[0]), null == e && (e = t), null == a && (n = a), null == n || null == a) var s = this.getBBox(1);
            return n = null == n ? s.x + s.width / 2 : n, a = null == a ? s.y + s.height / 2 : a, this.transform(this._.transform.concat([
                ["s", t, e, n, a]
            ])), this
        }, E.translate = function (t, e) {
            return this.removed ? this : (t = r(t).split(h), t.length - 1 && (e = i(t[1])), t = i(t[0]) || 0, e = +e || 0, this.transform(this._.transform.concat([
                ["t", t, e]
            ])), this)
        }, E.transform = function (r) {
            var i = this._;
            if (null == r) return i.transform;
            if (t._extractTransform(this, r), this.clip && x(this.clip, {
                    "transform": this.matrix.invert()
                }), this.pattern && b(this), this.node && x(this.node, {
                    "transform": this.matrix
                }), 1 != i.sx || 1 != i.sy) {
                var n = this.attrs[e]("stroke-width") ? this.attrs["stroke-width"] : 1;
                this.attr({
                    "stroke-width": n
                })
            }
            return i.transform = this.matrix.toTransformString(), this
        }, E.hide = function () {
            return this.removed || (this.node.style.display = "none"), this
        }, E.show = function () {
            return this.removed || (this.node.style.display = ""), this
        }, E.remove = function () {
            var e = T(this.node);
            if (!this.removed && e.parentNode) {
                var r = this.paper;
                r.__set__ && r.__set__.exclude(this), u.unbind("raphael.*.*." + this.id), this.gradient && r.defs.removeChild(this.gradient), t._tear(this, r), e.parentNode.removeChild(e), this.removeData();
                for (var i in this) this[i] = "function" == typeof this[i] ? t._removedFactory(i) : null;
                this.removed = !0
            }
        }, E._getBBox = function () {
            if ("none" == this.node.style.display) {
                this.show();
                var t = !0
            }
            var e, r = !1;
            this.paper.canvas.parentElement ? e = this.paper.canvas.parentElement.style : this.paper.canvas.parentNode && (e = this.paper.canvas.parentNode.style), e && "none" == e.display && (r = !0, e.display = "");
            var i = {};
            try {
                i = this.node.getBBox()
            } catch (n) {
                i = {
                    "x": this.node.clientLeft,
                    "y": this.node.clientTop,
                    "width": this.node.clientWidth,
                    "height": this.node.clientHeight
                }
            } finally {
                i = i || {}, r && (e.display = "none")
            }
            return t && this.hide(), i
        }, E.attr = function (r, i) {
            if (this.removed) return this;
            if (null == r) {
                var n = {};
                for (var a in this.attrs) this.attrs[e](a) && (n[a] = this.attrs[a]);
                return n.gradient && "none" == n.fill && (n.fill = n.gradient) && delete n.gradient, n.transform = this._.transform, n
            }
            if (null == i && t.is(r, "string")) {
                if ("fill" == r && "none" == this.attrs.fill && this.attrs.gradient) return this.attrs.gradient;
                if ("transform" == r) return this._.transform;
                for (var s = r.split(h), o = {}, l = 0, c = s.length; c > l; l++) r = s[l], o[r] = r in this.attrs ? this.attrs[r] : t.is(this.paper.customAttributes[r], "function") ? this.paper.customAttributes[r].def : t._availableAttrs[r];
                return c - 1 ? o : o[s[0]]
            }
            if (null == i && t.is(r, "array")) {
                for (o = {}, l = 0, c = r.length; c > l; l++) o[r[l]] = this.attr(r[l]);
                return o
            }
            if (null != i) {
                var f = {};
                f[r] = i
            } else null != r && t.is(r, "object") && (f = r);
            for (var p in f) u("raphael.attr." + p + "." + this.id, this, f[p]);
            for (p in this.paper.customAttributes)
                if (this.paper.customAttributes[e](p) && f[e](p) && t.is(this.paper.customAttributes[p], "function")) {
                    var d = this.paper.customAttributes[p].apply(this, [].concat(f[p]));
                    this.attrs[p] = f[p];
                    for (var g in d) d[e](g) && (f[g] = d[g])
                }
            return B(this, f), this
        }, E.toFront = function () {
            if (this.removed) return this;
            var e = T(this.node);
            e.parentNode.appendChild(e);
            var r = this.paper;
            return r.top != this && t._tofront(this, r), this
        }, E.toBack = function () {
            if (this.removed) return this;
            var e = T(this.node),
                r = e.parentNode;
            return r.insertBefore(e, r.firstChild), t._toback(this, this.paper), this.paper, this
        }, E.insertAfter = function (e) {
            if (this.removed || !e) return this;
            var r = T(this.node),
                i = T(e.node || e[e.length - 1].node);
            return i.nextSibling ? i.parentNode.insertBefore(r, i.nextSibling) : i.parentNode.appendChild(r), t._insertafter(this, e, this.paper), this
        }, E.insertBefore = function (e) {
            if (this.removed || !e) return this;
            var r = T(this.node),
                i = T(e.node || e[0].node);
            return i.parentNode.insertBefore(r, i), t._insertbefore(this, e, this.paper), this
        }, E.blur = function (e) {
            var r = this;
            if (0 !== +e) {
                var i = x("filter"),
                    n = x("feGaussianBlur");
                r.attrs.blur = e, i.id = t.createUUID(), x(n, {
                    "stdDeviation": +e || 1.5
                }), i.appendChild(n), r.paper.defs.appendChild(i), r._blur = i, x(r.node, {
                    "filter": "url(#" + i.id + ")"
                })
            } else r._blur && (r._blur.parentNode.removeChild(r._blur), delete r._blur, delete r.attrs.blur), r.node.removeAttribute("filter");
            return r
        }, t._engine.circle = function (t, e, r, i) {
            var n = x("circle");
            t.canvas && t.canvas.appendChild(n);
            var a = new A(n, t);
            return a.attrs = {
                "cx": e,
                "cy": r,
                "r": i,
                "fill": "none",
                "stroke": "#000"
            }, a.type = "circle", x(n, a.attrs), a
        }, t._engine.rect = function (t, e, r, i, n, a) {
            var s = x("rect");
            t.canvas && t.canvas.appendChild(s);
            var o = new A(s, t);
            return o.attrs = {
                "x": e,
                "y": r,
                "width": i,
                "height": n,
                "rx": a || 0,
                "ry": a || 0,
                "fill": "none",
                "stroke": "#000"
            }, o.type = "rect", x(s, o.attrs), o
        }, t._engine.ellipse = function (t, e, r, i, n) {
            var a = x("ellipse");
            t.canvas && t.canvas.appendChild(a);
            var s = new A(a, t);
            return s.attrs = {
                "cx": e,
                "cy": r,
                "rx": i,
                "ry": n,
                "fill": "none",
                "stroke": "#000"
            }, s.type = "ellipse", x(a, s.attrs), s
        }, t._engine.image = function (t, e, r, i, n, a) {
            var s = x("image");
            x(s, {
                "x": r,
                "y": i,
                "width": n,
                "height": a,
                "preserveAspectRatio": "none"
            }), s.setAttributeNS(p, "href", e), t.canvas && t.canvas.appendChild(s);
            var o = new A(s, t);
            return o.attrs = {
                "x": r,
                "y": i,
                "width": n,
                "height": a,
                "src": e
            }, o.type = "image", o
        }, t._engine.text = function (e, r, i, n) {
            var a = x("text");
            e.canvas && e.canvas.appendChild(a);
            var s = new A(a, e);
            return s.attrs = {
                "x": r,
                "y": i,
                "text-anchor": "middle",
                "text": n,
                "font-family": t._availableAttrs["font-family"],
                "font-size": t._availableAttrs["font-size"],
                "stroke": "none",
                "fill": "#000"
            }, s.type = "text", B(s, s.attrs), s
        }, t._engine.setSize = function (t, e) {
            return this.width = t || this.width, this.height = e || this.height, this.canvas.setAttribute("width", this.width), this.canvas.setAttribute("height", this.height), this._viewBox && this.setViewBox.apply(this, this._viewBox), this
        }, t._engine.create = function () {
            var e = t._getContainer.apply(0, arguments),
                r = e && e.container,
                i = e.x,
                n = e.y,
                a = e.width,
                s = e.height;
            if (!r) throw new Error("SVG container not found.");
            var o, l = x("svg"),
                h = "overflow:hidden;";
            return i = i || 0, n = n || 0, a = a || 512, s = s || 342, x(l, {
                "height": s,
                "version": 1.1,
                "width": a,
                "xmlns": "http://www.w3.org/2000/svg",
                "xmlns:xlink": "http://www.w3.org/1999/xlink"
            }), 1 == r ? (l.style.cssText = h + "position:absolute;left:" + i + "px;top:" + n + "px", t._g.doc.body.appendChild(l), o = 1) : (l.style.cssText = h + "position:relative", r.firstChild ? r.insertBefore(l, r.firstChild) : r.appendChild(l)), r = new t._Paper, r.width = a, r.height = s, r.canvas = l, r.clear(), r._left = r._top = 0, o && (r.renderfix = function () {}), r.renderfix(), r
        }, t._engine.setViewBox = function (t, e, r, i, n) {
            u("raphael.setViewBox", this, this._viewBox, [t, e, r, i, n]);
            var a, o, l = this.getSize(),
                h = s(r / l.width, i / l.height),
                c = this.top,
                p = n ? "xMidYMid meet" : "xMinYMin";
            for (null == t ? (this._vbSize && (h = 1), delete this._vbSize, a = "0 0 " + this.width + f + this.height) : (this._vbSize = h, a = t + f + e + f + r + f + i), x(this.canvas, {
                    "viewBox": a,
                    "preserveAspectRatio": p
                }); h && c;) o = "stroke-width" in c.attrs ? c.attrs["stroke-width"] : 1, c.attr({
                "stroke-width": o
            }), c._.dirty = 1, c._.dirtyT = 1, c = c.prev;
            return this._viewBox = [t, e, r, i, !!n], this
        }, t.prototype.renderfix = function () {
            var t, e = this.canvas,
                r = e.style;
            try {
                t = e.getScreenCTM() || e.createSVGMatrix()
            } catch (i) {
                t = e.createSVGMatrix()
            }
            var n = -t.e % 1,
                a = -t.f % 1;
            (n || a) && (n && (this._left = (this._left + n) % 1, r.left = this._left + "px"), a && (this._top = (this._top + a) % 1, r.top = this._top + "px"))
        }, t.prototype.clear = function () {
            t.eve("raphael.clear", this);
            for (var e = this.canvas; e.firstChild;) e.removeChild(e.firstChild);
            this.bottom = this.top = null, (this.desc = x("desc")).appendChild(t._g.doc.createTextNode("Created with Rapha\xebl " + t.version)), e.appendChild(this.desc), e.appendChild(this.defs = x("defs"))
        }, t.prototype.remove = function () {
            u("raphael.remove", this), this.canvas.parentNode && this.canvas.parentNode.removeChild(this.canvas);
            for (var e in this) this[e] = "function" == typeof this[e] ? t._removedFactory(e) : null
        };
        var N = t.st;
        for (var M in E) E[e](M) && !N[e](M) && (N[M] = function (t) {
            return function () {
                var e = arguments;
                return this.forEach(function (r) {
                    r[t].apply(r, e)
                })
            }
        }(M))
    }
}),
function (t, e) {
    "function" == typeof define && define.amd ? define("raphael.vml", ["raphael.core"], function (t) {
        return e(t)
    }) : e("object" == typeof exports ? require("./raphael.core") : t.Raphael)
}(this, function (t) {
    if (!t || t.vml) {
        var e = "hasOwnProperty",
            r = String,
            i = parseFloat,
            n = Math,
            a = n.round,
            s = n.max,
            o = n.min,
            l = n.abs,
            h = "fill",
            u = /[, ]+/,
            c = t.eve,
            f = " progid:DXImageTransform.Microsoft",
            p = " ",
            d = "",
            g = {
                "M": "m",
                "L": "l",
                "C": "c",
                "Z": "x",
                "m": "t",
                "l": "r",
                "c": "v",
                "z": "x"
            },
            x = /([clmz]),?([^clmz]*)/gi,
            v = / progid:\S+Blur\([^\)]+\)/g,
            y = /-?[^,\s-]+/g,
            m = "position:absolute;left:0;top:0;width:1px;height:1px;behavior:url(#default#VML)",
            b = 21600,
            _ = {
                "path": 1,
                "rect": 1,
                "image": 1
            },
            w = {
                "circle": 1,
                "ellipse": 1
            },
            k = function (e) {
                var i = /[ahqstv]/gi,
                    n = t._pathToAbsolute;
                if (r(e).match(i) && (n = t._path2curve), i = /[clmz]/g, n == t._pathToAbsolute && !r(e).match(i)) {
                    var s = r(e).replace(x, function (t, e, r) {
                        var i = [],
                            n = "m" == e.toLowerCase(),
                            s = g[e];
                        return r.replace(y, function (t) {
                            n && 2 == i.length && (s += i + g["m" == e ? "l" : "L"], i = []), i.push(a(t * b))
                        }), s + i
                    });
                    return s
                }
                var o, l, h = n(e);
                s = [];
                for (var u = 0, c = h.length; c > u; u++) {
                    o = h[u], l = h[u][0].toLowerCase(), "z" == l && (l = "x");
                    for (var f = 1, v = o.length; v > f; f++) l += a(o[f] * b) + (f != v - 1 ? "," : d);
                    s.push(l)
                }
                return s.join(p)
            },
            B = function (e, r, i) {
                var n = t.matrix();
                return n.rotate(-e, .5, .5), {
                    "dx": n.x(r, i),
                    "dy": n.y(r, i)
                }
            },
            C = function (t, e, r, i, n, a) {
                var s = t._,
                    o = t.matrix,
                    u = s.fillpos,
                    c = t.node,
                    f = c.style,
                    d = 1,
                    g = "",
                    x = b / e,
                    v = b / r;
                if (f.visibility = "hidden", e && r) {
                    if (c.coordsize = l(x) + p + l(v), f.rotation = a * (0 > e * r ? -1 : 1), a) {
                        var y = B(a, i, n);
                        i = y.dx, n = y.dy
                    }
                    if (0 > e && (g += "x"), 0 > r && (g += " y") && (d = -1), f.flip = g, c.coordorigin = i * -x + p + n * -v, u || s.fillsize) {
                        var m = c.getElementsByTagName(h);
                        m = m && m[0], c.removeChild(m), u && (y = B(a, o.x(u[0], u[1]), o.y(u[0], u[1])), m.position = y.dx * d + p + y.dy * d), s.fillsize && (m.size = s.fillsize[0] * l(e) + p + s.fillsize[1] * l(r)), c.appendChild(m)
                    }
                    f.visibility = "visible"
                }
            };
        t.toString = function () {
            return "Your browser doesn\u2019t support SVG. Falling down to VML.\nYou are running Rapha\xebl " + this.version
        };
        var S = function (t, e, i) {
                for (var n = r(e).toLowerCase().split("-"), a = i ? "end" : "start", s = n.length, o = "classic", l = "medium", h = "medium"; s--;) switch (n[s]) {
                    case "block":
                    case "classic":
                    case "oval":
                    case "diamond":
                    case "open":
                    case "none":
                        o = n[s];
                        break;
                    case "wide":
                    case "narrow":
                        h = n[s];
                        break;
                    case "long":
                    case "short":
                        l = n[s]
                }
                var u = t.node.getElementsByTagName("stroke")[0];
                u[a + "arrow"] = o, u[a + "arrowlength"] = l, u[a + "arrowwidth"] = h
            },
            T = function (n, l) {
                n.attrs = n.attrs || {};
                var c = n.node,
                    f = n.attrs,
                    g = c.style,
                    x = _[n.type] && (l.x != f.x || l.y != f.y || l.width != f.width || l.height != f.height || l.cx != f.cx || l.cy != f.cy || l.rx != f.rx || l.ry != f.ry || l.r != f.r),
                    v = w[n.type] && (f.cx != l.cx || f.cy != l.cy || f.r != l.r || f.rx != l.rx || f.ry != l.ry),
                    y = n;
                for (var m in l) l[e](m) && (f[m] = l[m]);
                if (x && (f.path = t._getPath[n.type](n), n._.dirty = 1), l.href && (c.href = l.href), l.title && (c.title = l.title), l.target && (c.target = l.target), l.cursor && (g.cursor = l.cursor), "blur" in l && n.blur(l.blur), (l.path && "path" == n.type || x) && (c.path = k(~r(f.path).toLowerCase().indexOf("r") ? t._pathToAbsolute(f.path) : f.path), n._.dirty = 1, "image" == n.type && (n._.fillpos = [f.x, f.y], n._.fillsize = [f.width, f.height], C(n, 1, 1, 0, 0, 0))), "transform" in l && n.transform(l.transform), v) {
                    var B = +f.cx,
                        T = +f.cy,
                        E = +f.rx || +f.r || 0,
                        N = +f.ry || +f.r || 0;
                    c.path = t.format("ar{0},{1},{2},{3},{4},{1},{4},{1}x", a((B - E) * b), a((T - N) * b), a((B + E) * b), a((T + N) * b), a(B * b)), n._.dirty = 1
                }
                if ("clip-rect" in l) {
                    var L = r(l["clip-rect"]).split(u);
                    if (4 == L.length) {
                        L[2] = +L[2] + +L[0], L[3] = +L[3] + +L[1];
                        var z = c.clipRect || t._g.doc.createElement("div"),
                            P = z.style;
                        P.clip = t.format("rect({1}px {2}px {3}px {0}px)", L), c.clipRect || (P.position = "absolute", P.top = 0, P.left = 0, P.width = n.paper.width + "px", P.height = n.paper.height + "px", c.parentNode.insertBefore(z, c), z.appendChild(c), c.clipRect = z)
                    }
                    l["clip-rect"] || c.clipRect && (c.clipRect.style.clip = "auto")
                }
                if (n.textpath) {
                    var F = n.textpath.style;
                    l.font && (F.font = l.font), l["font-family"] && (F.fontFamily = '"' + l["font-family"].split(",")[0].replace(/^['"]+|['"]+$/g, d) + '"'), l["font-size"] && (F.fontSize = l["font-size"]), l["font-weight"] && (F.fontWeight = l["font-weight"]), l["font-style"] && (F.fontStyle = l["font-style"])
                }
                if ("arrow-start" in l && S(y, l["arrow-start"]), "arrow-end" in l && S(y, l["arrow-end"], 1), null != l.opacity || null != l["stroke-width"] || null != l.fill || null != l.src || null != l.stroke || null != l["stroke-width"] || null != l["stroke-opacity"] || null != l["fill-opacity"] || null != l["stroke-dasharray"] || null != l["stroke-miterlimit"] || null != l["stroke-linejoin"] || null != l["stroke-linecap"]) {
                    var R = c.getElementsByTagName(h),
                        j = !1;
                    if (R = R && R[0], !R && (j = R = M(h)), "image" == n.type && l.src && (R.src = l.src), l.fill && (R.on = !0), (null == R.on || "none" == l.fill || null === l.fill) && (R.on = !1), R.on && l.fill) {
                        var I = r(l.fill).match(t._ISURL);
                        if (I) {
                            R.parentNode == c && c.removeChild(R), R.rotate = !0, R.src = I[1], R.type = "tile";
                            var q = n.getBBox(1);
                            R.position = q.x + p + q.y, n._.fillpos = [q.x, q.y], t._preload(I[1], function () {
                                n._.fillsize = [this.offsetWidth, this.offsetHeight]
                            })
                        } else R.color = t.getRGB(l.fill).hex, R.src = d, R.type = "solid", t.getRGB(l.fill).error && (y.type in {
                            "circle": 1,
                            "ellipse": 1
                        } || "r" != r(l.fill).charAt()) && A(y, l.fill, R) && (f.fill = "none", f.gradient = l.fill, R.rotate = !1)
                    }
                    if ("fill-opacity" in l || "opacity" in l) {
                        var D = ((+f["fill-opacity"] + 1 || 2) - 1) * ((+f.opacity + 1 || 2) - 1) * ((+t.getRGB(l.fill).o + 1 || 2) - 1);
                        D = o(s(D, 0), 1), R.opacity = D, R.src && (R.color = "none")
                    }
                    c.appendChild(R);
                    var V = c.getElementsByTagName("stroke") && c.getElementsByTagName("stroke")[0],
                        O = !1;
                    !V && (O = V = M("stroke")), (l.stroke && "none" != l.stroke || l["stroke-width"] || null != l["stroke-opacity"] || l["stroke-dasharray"] || l["stroke-miterlimit"] || l["stroke-linejoin"] || l["stroke-linecap"]) && (V.on = !0), ("none" == l.stroke || null === l.stroke || null == V.on || 0 == l.stroke || 0 == l["stroke-width"]) && (V.on = !1);
                    var Y = t.getRGB(l.stroke);
                    V.on && l.stroke && (V.color = Y.hex), D = ((+f["stroke-opacity"] + 1 || 2) - 1) * ((+f.opacity + 1 || 2) - 1) * ((+Y.o + 1 || 2) - 1);
                    var W = .75 * (i(l["stroke-width"]) || 1);
                    if (D = o(s(D, 0), 1), null == l["stroke-width"] && (W = f["stroke-width"]), l["stroke-width"] && (V.weight = W), W && 1 > W && (D *= W) && (V.weight = 1), V.opacity = D, l["stroke-linejoin"] && (V.joinstyle = l["stroke-linejoin"] || "miter"), V.miterlimit = l["stroke-miterlimit"] || 8, l["stroke-linecap"] && (V.endcap = "butt" == l["stroke-linecap"] ? "flat" : "square" == l["stroke-linecap"] ? "square" : "round"), "stroke-dasharray" in l) {
                        var G = {
                            "-": "shortdash",
                            ".": "shortdot",
                            "-.": "shortdashdot",
                            "-..": "shortdashdotdot",
                            ". ": "dot",
                            "- ": "dash",
                            "--": "longdash",
                            "- .": "dashdot",
                            "--.": "longdashdot",
                            "--..": "longdashdotdot"
                        };
                        V.dashstyle = G[e](l["stroke-dasharray"]) ? G[l["stroke-dasharray"]] : d
                    }
                    O && c.appendChild(V)
                }
                if ("text" == y.type) {
                    y.paper.canvas.style.display = d;
                    var H = y.paper.span,
                        X = 100,
                        U = f.font && f.font.match(/\d+(?:\.\d*)?(?=px)/);
                    g = H.style, f.font && (g.font = f.font), f["font-family"] && (g.fontFamily = f["font-family"]), f["font-weight"] && (g.fontWeight = f["font-weight"]), f["font-style"] && (g.fontStyle = f["font-style"]), U = i(f["font-size"] || U && U[0]) || 10, g.fontSize = U * X + "px", y.textpath.string && (H.innerHTML = r(y.textpath.string).replace(/</g, "&#60;").replace(/&/g, "&#38;").replace(/\n/g, "<br>"));
                    var $ = H.getBoundingClientRect();
                    y.W = f.w = ($.right - $.left) / X, y.H = f.h = ($.bottom - $.top) / X, y.X = f.x, y.Y = f.y + y.H / 2, ("x" in l || "y" in l) && (y.path.v = t.format("m{0},{1}l{2},{1}", a(f.x * b), a(f.y * b), a(f.x * b) + 1));
                    for (var Z = ["x", "y", "text", "font", "font-family", "font-weight", "font-style", "font-size"], Q = 0, J = Z.length; J > Q; Q++)
                        if (Z[Q] in l) {
                            y._.dirty = 1;
                            break
                        }
                    switch (f["text-anchor"]) {
                        case "start":
                            y.textpath.style["v-text-align"] = "left", y.bbx = y.W / 2;
                            break;
                        case "end":
                            y.textpath.style["v-text-align"] = "right", y.bbx = -y.W / 2;
                            break;
                        default:
                            y.textpath.style["v-text-align"] = "center", y.bbx = 0
                    }
                    y.textpath.style["v-text-kern"] = !0
                }
            },
            A = function (e, a, s) {
                e.attrs = e.attrs || {};
                var o = (e.attrs, Math.pow),
                    l = "linear",
                    h = ".5 .5";
                if (e.attrs.gradient = a, a = r(a).replace(t._radial_gradient, function (t, e, r) {
                        return l = "radial", e && r && (e = i(e), r = i(r), o(e - .5, 2) + o(r - .5, 2) > .25 && (r = n.sqrt(.25 - o(e - .5, 2)) * (2 * (r > .5) - 1) + .5), h = e + p + r), d
                    }), a = a.split(/\s*\-\s*/), "linear" == l) {
                    var u = a.shift();
                    if (u = -i(u), isNaN(u)) return null
                }
                var c = t._parseDots(a);
                if (!c) return null;
                if (e = e.shape || e.node, c.length) {
                    e.removeChild(s), s.on = !0, s.method = "none", s.color = c[0].color, s.color2 = c[c.length - 1].color;
                    for (var f = [], g = 0, x = c.length; x > g; g++) c[g].offset && f.push(c[g].offset + p + c[g].color);
                    s.colors = f.length ? f.join() : "0% " + s.color, "radial" == l ? (s.type = "gradientTitle", s.focus = "100%", s.focussize = "0 0", s.focusposition = h, s.angle = 0) : (s.type = "gradient", s.angle = (270 - u) % 360), e.appendChild(s)
                }
                return 1
            },
            E = function (e, r) {
                this[0] = this.node = e, e.raphael = !0, this.id = t._oid++, e.raphaelid = this.id, this.X = 0, this.Y = 0, this.attrs = {}, this.paper = r, this.matrix = t.matrix(), this._ = {
                    "transform": [],
                    "sx": 1,
                    "sy": 1,
                    "dx": 0,
                    "dy": 0,
                    "deg": 0,
                    "dirty": 1,
                    "dirtyT": 1
                }, !r.bottom && (r.bottom = this), this.prev = r.top, r.top && (r.top.next = this), r.top = this, this.next = null
            },
            N = t.el;
        E.prototype = N, N.constructor = E, N.transform = function (e) {
            if (null == e) return this._.transform;
            var i, n = this.paper._viewBoxShift,
                a = n ? "s" + [n.scale, n.scale] + "-1-1t" + [n.dx, n.dy] : d;
            n && (i = e = r(e).replace(/\.{3}|\u2026/g, this._.transform || d)), t._extractTransform(this, a + e);
            var s, o = this.matrix.clone(),
                l = this.skew,
                h = this.node,
                u = ~r(this.attrs.fill).indexOf("-"),
                c = !r(this.attrs.fill).indexOf("url(");
            if (o.translate(1, 1), c || u || "image" == this.type)
                if (l.matrix = "1 0 0 1", l.offset = "0 0", s = o.split(), u && s.noRotation || !s.isSimple) {
                    h.style.filter = o.toFilter();
                    var f = this.getBBox(),
                        g = this.getBBox(1),
                        x = f.x - g.x,
                        v = f.y - g.y;
                    h.coordorigin = x * -b + p + v * -b, C(this, 1, 1, x, v, 0)
                } else h.style.filter = d, C(this, s.scalex, s.scaley, s.dx, s.dy, s.rotate);
            else h.style.filter = d, l.matrix = r(o), l.offset = o.offset();
            return null !== i && (this._.transform = i, t._extractTransform(this, i)), this
        }, N.rotate = function (t, e, n) {
            if (this.removed) return this;
            if (null != t) {
                if (t = r(t).split(u), t.length - 1 && (e = i(t[1]), n = i(t[2])), t = i(t[0]), null == n && (e = n), null == e || null == n) {
                    var a = this.getBBox(1);
                    e = a.x + a.width / 2, n = a.y + a.height / 2
                }
                return this._.dirtyT = 1, this.transform(this._.transform.concat([
                    ["r", t, e, n]
                ])), this
            }
        }, N.translate = function (t, e) {
            return this.removed ? this : (t = r(t).split(u), t.length - 1 && (e = i(t[1])), t = i(t[0]) || 0, e = +e || 0, this._.bbox && (this._.bbox.x += t, this._.bbox.y += e), this.transform(this._.transform.concat([
                ["t", t, e]
            ])), this)
        }, N.scale = function (t, e, n, a) {
            if (this.removed) return this;
            if (t = r(t).split(u), t.length - 1 && (e = i(t[1]), n = i(t[2]), a = i(t[3]), isNaN(n) && (n = null), isNaN(a) && (a = null)), t = i(t[0]), null == e && (e = t), null == a && (n = a), null == n || null == a) var s = this.getBBox(1);
            return n = null == n ? s.x + s.width / 2 : n, a = null == a ? s.y + s.height / 2 : a, this.transform(this._.transform.concat([
                ["s", t, e, n, a]
            ])), this._.dirtyT = 1, this
        }, N.hide = function () {
            return !this.removed && (this.node.style.display = "none"), this
        }, N.show = function () {
            return !this.removed && (this.node.style.display = d), this
        }, N.auxGetBBox = t.el.getBBox, N.getBBox = function () {
            var t = this.auxGetBBox();
            if (this.paper && this.paper._viewBoxShift) {
                var e = {},
                    r = 1 / this.paper._viewBoxShift.scale;
                return e.x = t.x - this.paper._viewBoxShift.dx, e.x *= r, e.y = t.y - this.paper._viewBoxShift.dy, e.y *= r, e.width = t.width * r, e.height = t.height * r, e.x2 = e.x + e.width, e.y2 = e.y + e.height, e
            }
            return t
        }, N._getBBox = function () {
            return this.removed ? {} : {
                "x": this.X + (this.bbx || 0) - this.W / 2,
                "y": this.Y - this.H,
                "width": this.W,
                "height": this.H
            }
        }, N.remove = function () {
            if (!this.removed && this.node.parentNode) {
                this.paper.__set__ && this.paper.__set__.exclude(this), t.eve.unbind("raphael.*.*." + this.id), t._tear(this, this.paper), this.node.parentNode.removeChild(this.node), this.shape && this.shape.parentNode.removeChild(this.shape);
                for (var e in this) this[e] = "function" == typeof this[e] ? t._removedFactory(e) : null;
                this.removed = !0
            }
        }, N.attr = function (r, i) {
            if (this.removed) return this;
            if (null == r) {
                var n = {};
                for (var a in this.attrs) this.attrs[e](a) && (n[a] = this.attrs[a]);
                return n.gradient && "none" == n.fill && (n.fill = n.gradient) && delete n.gradient, n.transform = this._.transform, n
            }
            if (null == i && t.is(r, "string")) {
                if (r == h && "none" == this.attrs.fill && this.attrs.gradient) return this.attrs.gradient;
                for (var s = r.split(u), o = {}, l = 0, f = s.length; f > l; l++) r = s[l], o[r] = r in this.attrs ? this.attrs[r] : t.is(this.paper.customAttributes[r], "function") ? this.paper.customAttributes[r].def : t._availableAttrs[r];
                return f - 1 ? o : o[s[0]]
            }
            if (this.attrs && null == i && t.is(r, "array")) {
                for (o = {}, l = 0, f = r.length; f > l; l++) o[r[l]] = this.attr(r[l]);
                return o
            }
            var p;
            null != i && (p = {}, p[r] = i), null == i && t.is(r, "object") && (p = r);
            for (var d in p) c("raphael.attr." + d + "." + this.id, this, p[d]);
            if (p) {
                for (d in this.paper.customAttributes)
                    if (this.paper.customAttributes[e](d) && p[e](d) && t.is(this.paper.customAttributes[d], "function")) {
                        var g = this.paper.customAttributes[d].apply(this, [].concat(p[d]));
                        this.attrs[d] = p[d];
                        for (var x in g) g[e](x) && (p[x] = g[x])
                    }
                p.text && "text" == this.type && (this.textpath.string = p.text), T(this, p)
            }
            return this
        }, N.toFront = function () {
            return !this.removed && this.node.parentNode.appendChild(this.node), this.paper && this.paper.top != this && t._tofront(this, this.paper), this
        }, N.toBack = function () {
            return this.removed ? this : (this.node.parentNode.firstChild != this.node && (this.node.parentNode.insertBefore(this.node, this.node.parentNode.firstChild), t._toback(this, this.paper)), this)
        }, N.insertAfter = function (e) {
            return this.removed ? this : (e.constructor == t.st.constructor && (e = e[e.length - 1]), e.node.nextSibling ? e.node.parentNode.insertBefore(this.node, e.node.nextSibling) : e.node.parentNode.appendChild(this.node), t._insertafter(this, e, this.paper), this)
        }, N.insertBefore = function (e) {
            return this.removed ? this : (e.constructor == t.st.constructor && (e = e[0]), e.node.parentNode.insertBefore(this.node, e.node), t._insertbefore(this, e, this.paper), this)
        }, N.blur = function (e) {
            var r = this.node.runtimeStyle,
                i = r.filter;
            return i = i.replace(v, d), 0 !== +e ? (this.attrs.blur = e, r.filter = i + p + f + ".Blur(pixelradius=" + (+e || 1.5) + ")", r.margin = t.format("-{0}px 0 0 -{0}px", a(+e || 1.5))) : (r.filter = i, r.margin = 0, delete this.attrs.blur), this
        }, t._engine.path = function (t, e) {
            var r = M("shape");
            r.style.cssText = m, r.coordsize = b + p + b, r.coordorigin = e.coordorigin;
            var i = new E(r, e),
                n = {
                    "fill": "none",
                    "stroke": "#000"
                };
            t && (n.path = t), i.type = "path", i.path = [], i.Path = d, T(i, n), e.canvas && e.canvas.appendChild(r);
            var a = M("skew");
            return a.on = !0, r.appendChild(a), i.skew = a, i.transform(d), i
        }, t._engine.rect = function (e, r, i, n, a, s) {
            var o = t._rectPath(r, i, n, a, s),
                l = e.path(o),
                h = l.attrs;
            return l.X = h.x = r, l.Y = h.y = i, l.W = h.width = n, l.H = h.height = a, h.r = s, h.path = o, l.type = "rect", l
        }, t._engine.ellipse = function (t, e, r, i, n) {
            var a = t.path();
            return a.attrs, a.X = e - i, a.Y = r - n, a.W = 2 * i, a.H = 2 * n, a.type = "ellipse", T(a, {
                "cx": e,
                "cy": r,
                "rx": i,
                "ry": n
            }), a
        }, t._engine.circle = function (t, e, r, i) {
            var n = t.path();
            return n.attrs, n.X = e - i, n.Y = r - i, n.W = n.H = 2 * i, n.type = "circle", T(n, {
                "cx": e,
                "cy": r,
                "r": i
            }), n
        }, t._engine.image = function (e, r, i, n, a, s) {
            var o = t._rectPath(i, n, a, s),
                l = e.path(o).attr({
                    "stroke": "none"
                }),
                u = l.attrs,
                c = l.node,
                f = c.getElementsByTagName(h)[0];
            return u.src = r, l.X = u.x = i, l.Y = u.y = n, l.W = u.width = a, l.H = u.height = s, u.path = o, l.type = "image", f.parentNode == c && c.removeChild(f), f.rotate = !0, f.src = r, f.type = "tile", l._.fillpos = [i, n], l._.fillsize = [a, s], c.appendChild(f), C(l, 1, 1, 0, 0, 0), l
        }, t._engine.text = function (e, i, n, s) {
            var o = M("shape"),
                l = M("path"),
                h = M("textpath");
            i = i || 0, n = n || 0, s = s || "", l.v = t.format("m{0},{1}l{2},{1}", a(i * b), a(n * b), a(i * b) + 1), l.textpathok = !0, h.string = r(s), h.on = !0, o.style.cssText = m, o.coordsize = b + p + b, o.coordorigin = "0 0";
            var u = new E(o, e),
                c = {
                    "fill": "#000",
                    "stroke": "none",
                    "font": t._availableAttrs.font,
                    "text": s
                };
            u.shape = o, u.path = l, u.textpath = h, u.type = "text", u.attrs.text = r(s), u.attrs.x = i, u.attrs.y = n, u.attrs.w = 1, u.attrs.h = 1, T(u, c), o.appendChild(h), o.appendChild(l), e.canvas.appendChild(o);
            var f = M("skew");
            return f.on = !0, o.appendChild(f), u.skew = f, u.transform(d), u
        }, t._engine.setSize = function (e, r) {
            var i = this.canvas.style;
            return this.width = e, this.height = r, e == +e && (e += "px"), r == +r && (r += "px"), i.width = e, i.height = r, i.clip = "rect(0 " + e + " " + r + " 0)", this._viewBox && t._engine.setViewBox.apply(this, this._viewBox), this
        }, t._engine.setViewBox = function (e, r, i, n, a) {
            t.eve("raphael.setViewBox", this, this._viewBox, [e, r, i, n, a]);
            var s, o, l = this.getSize(),
                h = l.width,
                u = l.height;
            return a && (s = u / n, o = h / i, h > i * s && (e -= (h - i * s) / 2 / s), u > n * o && (r -= (u - n * o) / 2 / o)), this._viewBox = [e, r, i, n, !!a], this._viewBoxShift = {
                "dx": -e,
                "dy": -r,
                "scale": l
            }, this.forEach(function (t) {
                t.transform("...")
            }), this
        };
        var M;
        t._engine.initWin = function (t) {
            var e = t.document;
            e.styleSheets.length < 31 ? e.createStyleSheet().addRule(".rvml", "behavior:url(#default#VML)") : e.styleSheets[0].addRule(".rvml", "behavior:url(#default#VML)");
            try {
                !e.namespaces.rvml && e.namespaces.add("rvml", "urn:schemas-microsoft-com:vml"), M = function (t) {
                    return e.createElement("<rvml:" + t + ' class="rvml">')
                }
            } catch (r) {
                M = function (t) {
                    return e.createElement("<" + t + ' xmlns="urn:schemas-microsoft.com:vml" class="rvml">')
                }
            }
        }, t._engine.initWin(t._g.win), t._engine.create = function () {
            var e = t._getContainer.apply(0, arguments),
                r = e.container,
                i = e.height,
                n = e.width,
                a = e.x,
                s = e.y;
            if (!r) throw new Error("VML container not found.");
            var o = new t._Paper,
                l = o.canvas = t._g.doc.createElement("div"),
                h = l.style;
            return a = a || 0, s = s || 0, n = n || 512, i = i || 342, o.width = n, o.height = i, n == +n && (n += "px"), i == +i && (i += "px"), o.coordsize = 1e3 * b + p + 1e3 * b, o.coordorigin = "0 0", o.span = t._g.doc.createElement("span"), o.span.style.cssText = "position:absolute;left:-9999em;top:-9999em;padding:0;margin:0;line-height:1;", l.appendChild(o.span), h.cssText = t.format("top:0;left:0;width:{0};height:{1};display:inline-block;position:relative;clip:rect(0 {0} {1} 0);overflow:hidden", n, i), 1 == r ? (t._g.doc.body.appendChild(l), h.left = a + "px", h.top = s + "px", h.position = "absolute") : r.firstChild ? r.insertBefore(l, r.firstChild) : r.appendChild(l), o.renderfix = function () {}, o
        }, t.prototype.clear = function () {
            t.eve("raphael.clear", this), this.canvas.innerHTML = d, this.span = t._g.doc.createElement("span"), this.span.style.cssText = "position:absolute;left:-9999em;top:-9999em;padding:0;margin:0;line-height:1;display:inline;", this.canvas.appendChild(this.span), this.bottom = this.top = null
        }, t.prototype.remove = function () {
            t.eve("raphael.remove", this), this.canvas.parentNode.removeChild(this.canvas);
            for (var e in this) this[e] = "function" == typeof this[e] ? t._removedFactory(e) : null;
            return !0
        };
        var L = t.st;
        for (var z in N) N[e](z) && !L[e](z) && (L[z] = function (t) {
            return function () {
                var e = arguments;
                return this.forEach(function (r) {
                    r[t].apply(r, e)
                })
            }
        }(z))
    }
}),
function (t, e) {
    if ("function" == typeof define && define.amd) define("raphael", ["raphael.core", "raphael.svg", "raphael.vml"], function (r) {
        return t.Raphael = e(r)
    });
    else if ("object" == typeof exports) {
        var r = require("raphael.core");
        require("raphael.svg"), require("raphael.vml"), module.exports = e(r)
    } else t.Raphael = e(t.Raphael)
}(this, function (t) {
    return t.ninja()
});