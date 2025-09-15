// utils/date.ts
import { formatDistance, parseISO } from 'date-fns';

/**
 * Relative date like "3 days ago"
 */
export const formatRelative = (date: string | Date): string => {
    const parsed = typeof date === 'string' ? parseISO(date) : date;
    return formatDistance(parsed, new Date(), { addSuffix: true });
};

/**
 * Absolute date in human-friendly format
 * e.g. "Monday, September 8, 2025 at 10:15 AM"
 */
export const formatFull = (date: string | Date = new Date(), locale: string = 'en-CA'): string => {
    const parsed = typeof date === 'string' ? new Date(date) : date;
    return new Intl.DateTimeFormat(locale, {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    }).format(parsed);
};

/**
 * Customizable formatter
 */
export const formatDate = (
    date: string | Date,
    options: Intl.DateTimeFormatOptions = {
        year: 'numeric',
        month: 'numeric',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
    },
    locale: string = 'en-CA',
): string => {
    const parsed = typeof date === 'string' ? new Date(date) : date;

    return parsed ? new Intl.DateTimeFormat(locale, options).format(parsed) : 'null';
};
