import { clsx } from "clsx";
import { twMerge } from "tailwind-merge";

/**
 * Utility function to merge Tailwind CSS classes intelligently
 * Combines clsx for conditional classes and tailwind-merge for conflict resolution
 * 
 * @param {...any} inputs - Class names, objects, arrays, or conditional expressions
 * @returns {string} - Merged and optimized class string
 * 
 * @example
 * cn('px-2 py-1', 'px-4') // Returns: 'py-1 px-4' (px-2 is overridden)
 * cn('text-red-500', { 'text-blue-500': isActive }) // Conditional classes
 * cn(['bg-white', 'shadow-md'], 'hover:shadow-lg') // Array support
 */
export function cn(...inputs) {
  return twMerge(clsx(inputs));
}

// Default export for convenience
export default cn;