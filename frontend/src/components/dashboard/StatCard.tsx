import { LucideIcon } from "lucide-react";

interface StatCardProps {
  label: string;
  value: string | number;
  icon: LucideIcon;
  trend?: string;
  accent?: "primary" | "accent" | "secondary";
}

const accentMap = {
  primary: "bg-primary/10 text-primary",
  accent: "bg-accent/10 text-accent",
  secondary: "bg-secondary/10 text-secondary-700",
};

export function StatCard({ label, value, icon: Icon, trend, accent = "primary" }: StatCardProps) {
  return (
    <div className="card flex items-start justify-between">
      <div>
        <p className="text-sm text-gray-500 dark:text-gray-400">{label}</p>
        <p className="mt-1 text-2xl font-semibold text-secondary-900 dark:text-white">{value}</p>
        {trend && <p className="mt-1 text-xs text-accent-600">{trend}</p>}
      </div>
      <div className={`rounded-lg p-2.5 ${accentMap[accent]}`}>
        <Icon size={20} />
      </div>
    </div>
  );
}
