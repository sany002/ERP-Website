"use client";

import { useTranslation } from "react-i18next";
import { useDispatch, useSelector } from "react-redux";
import { Moon, Sun, Search } from "lucide-react";
import { RootState } from "@/store";
import { toggleTheme } from "@/store/themeSlice";

export function Topbar() {
  const { t, i18n } = useTranslation();
  const dispatch = useDispatch();
  const themeMode = useSelector((s: RootState) => s.theme.mode);

  const switchLanguage = (lng: "en" | "bn") => {
    // Instant switch — no page reload, react-i18next re-renders all bound strings.
    i18n.changeLanguage(lng);
  };

  return (
    <header className="glass-panel sticky top-0 z-20 mb-6 flex items-center justify-between px-5 py-3">
      <div className="flex w-full max-w-sm items-center gap-2 rounded-lg border border-gray-200 px-3 py-1.5 dark:border-secondary-700">
        <Search size={16} className="text-gray-400" />
        <input
          placeholder={t("common.search") + "..."}
          className="w-full bg-transparent text-sm outline-none placeholder:text-gray-400"
        />
      </div>

      <div className="flex items-center gap-3">
        <div className="flex overflow-hidden rounded-lg border border-gray-200 text-xs dark:border-secondary-700">
          <button
            onClick={() => switchLanguage("en")}
            className={`px-2.5 py-1 ${i18n.language === "en" ? "bg-primary text-white" : "text-gray-500"}`}
          >
            EN
          </button>
          <button
            onClick={() => switchLanguage("bn")}
            className={`px-2.5 py-1 ${i18n.language === "bn" ? "bg-primary text-white" : "text-gray-500"}`}
          >
            বাং
          </button>
        </div>

        <button
          onClick={() => dispatch(toggleTheme())}
          className="rounded-lg border border-gray-200 p-2 text-gray-600 hover:bg-gray-100 dark:border-secondary-700 dark:text-gray-300 dark:hover:bg-secondary-700"
          aria-label={themeMode === "light" ? t("common.darkMode") : t("common.lightMode")}
        >
          {themeMode === "light" ? <Moon size={16} /> : <Sun size={16} />}
        </button>
      </div>
    </header>
  );
}
