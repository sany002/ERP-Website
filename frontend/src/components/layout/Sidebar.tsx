"use client";

import { useTranslation } from "react-i18next";
import {
  LayoutDashboard, Car, Package, Calculator, ShoppingCart,
  Truck, Users, BarChart3, Settings, ClipboardList, DoorOpen,
} from "lucide-react";
import { cn } from "@/lib/utils";

const navItems = [
  { key: "dashboard", icon: LayoutDashboard, href: "/dashboard" },
  { key: "vehicles", icon: Car, href: "/vehicles" },
  { key: "jobs", icon: ClipboardList, href: "/jobs" },
  { key: "gateLog", icon: DoorOpen, href: "/gate-log" },
  { key: "inventory", icon: Package, href: "/inventory" },
  { key: "accounting", icon: Calculator, href: "/accounting" },
  { key: "sales", icon: ShoppingCart, href: "/sales" },
  { key: "purchases", icon: Truck, href: "/purchases" },
  { key: "hr", icon: Users, href: "/hr" },
  { key: "reports", icon: BarChart3, href: "/reports" },
  { key: "settings", icon: Settings, href: "/settings" },
];

export function Sidebar({ activeHref = "/dashboard" }: { activeHref?: string }) {
  const { t } = useTranslation();

  return (
    <aside className="glass-panel fixed left-0 top-0 z-30 hidden h-screen w-64 flex-col p-4 md:flex">
      <div className="mb-8 flex items-center gap-2 px-2">
        <div className="flex h-9 w-9 items-center justify-center rounded-lg bg-primary text-lg font-bold text-white">
          S
        </div>
        <div>
          <p className="text-sm font-semibold leading-tight text-secondary-900 dark:text-white">
            {t("app.name")}
          </p>
          <p className="text-[11px] leading-tight text-gray-500 dark:text-gray-400">
            {t("app.developedBy")}
          </p>
        </div>
      </div>

      <nav className="flex-1 space-y-1">
        {navItems.map(({ key, icon: Icon, href }) => (
          <a
            key={key}
            href={href}
            className={cn(
              "flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors",
              href === activeHref
                ? "bg-primary/10 text-primary"
                : "text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-secondary-700"
            )}
          >
            <Icon size={18} />
            {t(`nav.${key}`)}
          </a>
        ))}
      </nav>
    </aside>
  );
}
