"use client";

import { useEffect, useState } from "react";
import { Provider, useSelector } from "react-redux";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { I18nextProvider } from "react-i18next";
import { store, RootState } from "@/store";
import i18n from "@/i18n/config";

function ThemeSync() {
  const mode = useSelector((s: RootState) => s.theme.mode);

  useEffect(() => {
    document.documentElement.classList.toggle("dark", mode === "dark");
  }, [mode]);

  return null;
}

export function Providers({ children }: { children: React.ReactNode }) {
  const [queryClient] = useState(() => new QueryClient());

  return (
    <Provider store={store}>
      <QueryClientProvider client={queryClient}>
        <I18nextProvider i18n={i18n}>
          <ThemeSync />
          {children}
        </I18nextProvider>
      </QueryClientProvider>
    </Provider>
  );
}
