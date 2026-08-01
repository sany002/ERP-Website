"use client";

import i18n from "i18next";
import { initReactI18next } from "react-i18next";
import LanguageDetector from "i18next-browser-languagedetector";

import en from "./locales/en.json";
import bn from "./locales/bn.json";

i18n
  .use(LanguageDetector)
  .use(initReactI18next)
  .init({
    resources: {
      en: { translation: en },
      bn: { translation: bn },
    },
    fallbackLng: "en",
    supportedLngs: ["en", "bn"],
    interpolation: { escapeValue: false },
    detection: {
      // Persist choice in localStorage so it survives reloads, but switching
      // is instant and requires no reload/navigation.
      order: ["localStorage", "navigator"],
      caches: ["localStorage"],
    },
  });

export default i18n;
