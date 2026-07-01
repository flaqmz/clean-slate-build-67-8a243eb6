import { createFileRoute } from "@tanstack/react-router";
import { useEffect, useState } from "react";
import { Sun, Star, Clock, Plus, ArrowRight } from "lucide-react";
import bridgeBg from "@/assets/bridge.webp.asset.json";

export const Route = createFileRoute("/")({
  head: () => ({
    meta: [
      { title: "Sunora — Summer Drop" },
      { name: "description", content: "Zu heiss, um falsch angezogen zu sein. Sichere dir jetzt deine Sunora Pieces." },
      { property: "og:title", content: "Sunora — Summer Drop" },
      { property: "og:description", content: "Zu heiss, um falsch angezogen zu sein." },
    ],
  }),
  component: Index,
});

function useCountdown(initial: number) {
  const [s, setS] = useState(initial);
  useEffect(() => {
    if (s <= 0) return;
    const id = setInterval(() => setS((v) => (v > 0 ? v - 1 : 0)), 1000);
    return () => clearInterval(id);
  }, [s]);
  const mm = String(Math.floor(s / 60)).padStart(2, "0");
  const ss = String(s % 60).padStart(2, "0");
  return `${mm}:${ss}`;
}

function Index() {
  const time = useCountdown(178);

  return (
    <div
      className="min-h-screen text-white font-[Inter,sans-serif] flex justify-center relative bg-[#0e0e0e]"
      style={{
        backgroundImage: `linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.75)), url(${bridgeBg.url})`,
        backgroundSize: "cover",
        backgroundPosition: "center",
        backgroundAttachment: "fixed",
      }}
    >
      <div className="w-full max-w-md px-5 pt-8 pb-16 flex flex-col items-center relative z-10">
        {/* Summer drop pill */}
        <div className="inline-flex items-center gap-2 rounded-full border border-white/25 px-4 py-1.5 text-[11px] font-semibold tracking-[0.18em]">
          <Sun className="h-3.5 w-3.5" strokeWidth={2} />
          SUMMER DROP
        </div>

        {/* Wordmark */}
        <h1
          className="mt-6 text-4xl tracking-[0.15em] font-semibold italic"
          style={{ fontFamily: "'Playfair Display', serif", color: "#E5B34B" }}
        >
          SUNORA
        </h1>

        {/* Bonus card */}
        <div className="mt-5 w-full rounded-2xl bg-[#1a1a1a]/85 backdrop-blur border border-white/10 p-4 shadow-2xl">
          <div className="flex items-start justify-between gap-3">
            <div className="flex-1">
              <span className="inline-block rounded-full bg-black px-3 py-1 text-[10px] font-bold tracking-wider">
                BONUS AKTIV
              </span>
              <div className="mt-2 text-[17px] font-bold leading-tight">2x Gratis Geschenke</div>
              <div className="text-[11px] text-white/60 mt-0.5">
                Nur für kurze Zeit zu jeder Bestellung
              </div>
            </div>
            <div
              className="rounded-md px-3 py-2 text-base font-bold tabular-nums text-black"
              style={{ background: "#C9A574" }}
            >
              {time}
            </div>
          </div>

          <div className="mt-3 grid grid-cols-2 gap-2">
            {["Rimowa Case", "YSL Armband"].map((label) => (
              <div
                key={label}
                className="flex items-center gap-2 rounded-xl bg-white/95 text-black px-2.5 py-2"
              >
                <div className="h-7 w-7 rounded-md bg-neutral-200" />
                <Plus className="h-3.5 w-3.5" strokeWidth={2.5} />
                <span className="text-[12px] font-semibold truncate">{label}</span>
              </div>
            ))}
          </div>
        </div>

        {/* Headline */}
        <h2
          className="mt-8 self-start text-[44px] leading-[0.95] font-normal uppercase"
          style={{ fontFamily: "Anton, sans-serif", letterSpacing: "0.01em" }}
        >
          Zu heiss,
          <br />
          um falsch
          <br />
          angezogen
          <br />
          zu sein.
        </h2>

        {/* Social proof */}
        <div className="mt-8 self-start flex items-start gap-2">
          <Star className="h-4 w-4 mt-0.5 shrink-0" fill="#E5B34B" stroke="#E5B34B" />
          <p className="text-[14px] font-semibold leading-snug">
            Über 12.000 Kunden tragen unsere
            <br />
            Pieces
          </p>
        </div>

        {/* CTA */}
        <button className="mt-5 w-full rounded-2xl bg-[#efeae1] text-black py-4 text-[13px] font-bold tracking-wider shadow-lg hover:bg-white transition-colors">
          JETZT SOMMER FIT SICHERN
          <br />
          <span className="inline-flex items-center gap-1">
            <ArrowRight className="h-3.5 w-3.5" strokeWidth={3} />
            HIER KLICKEN
          </span>
        </button>

        {/* Footer note */}
        <div className="mt-4 self-start flex items-start gap-2 text-white/55">
          <Clock className="h-3.5 w-3.5 mt-0.5 shrink-0" />
          <p className="text-[11px] leading-snug">
            Warte nicht — sichere dir deine Größe, bevor sie weg ist.
          </p>
        </div>
      </div>
    </div>
  );
}
