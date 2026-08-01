"use client";

import { useEffect } from "react";
import { useRouter } from "next/navigation";
import { useSelector } from "react-redux";
import { RootState } from "@/store";

export default function RootPage() {
  const router = useRouter();
  const token = useSelector((s: RootState) => s.auth.token);

  useEffect(() => {
    router.replace(token ? "/dashboard" : "/login");
  }, [token, router]);

  return (
    <div className="flex min-h-screen items-center justify-center bg-gray-50 dark:bg-secondary-900">
      <div className="h-8 w-8 animate-spin rounded-full border-2 border-primary border-t-transparent" />
    </div>
  );
}
