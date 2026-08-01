"use client";

import Link from "next/link";

export default function NotFound() {
  return (
    <div className="flex min-h-screen flex-col items-center justify-center gap-3 bg-gray-50 px-4 text-center dark:bg-secondary-900">
      <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-primary text-xl font-bold text-white">
        S
      </div>
      <h1 className="text-2xl font-semibold text-secondary-900 dark:text-white">404 — Page not found</h1>
      <p className="max-w-sm text-sm text-gray-500 dark:text-gray-400">
        The page you're looking for doesn't exist in Synex ERP.
      </p>
      <Link href="/dashboard" className="btn-primary mt-2">
        Back to Dashboard
      </Link>
    </div>
  );
}
