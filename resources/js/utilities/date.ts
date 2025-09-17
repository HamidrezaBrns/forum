// utils/date.ts
import { formatDistance, parseISO } from 'date-fns';
import { enUS, faIR } from 'date-fns/locale';
import { toPersianDigits } from './number';

function getLocale(locale: string) {
    return locale.startsWith('fa') ? faIR : enUS;
}

/**
 * Relative date like "3 days ago" / "۳ روز پیش"
 */
export const formatRelative = (date: string | Date, locale: string = document.documentElement.lang): string => {
    const parsed = typeof date === 'string' ? parseISO(date) : date;

    let result = formatDistance(parsed, new Date(), {
        addSuffix: true,
        locale: getLocale(locale),
    });

    if (locale.startsWith('fa')) {
        result = toPersianDigits(result);
    }

    return result;
};

/**
 * Absolute date in human-friendly format
 * e.g. "Monday, September 8, 2025 at 10:15 AM"
 */
export const formatFull = (date: string | Date = new Date(), locale: string = document.documentElement.lang): string => {
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
    locale: string = document.documentElement.lang,
): string => {
    const parsed = typeof date === 'string' ? new Date(date) : date;
    return parsed ? new Intl.DateTimeFormat(locale, options).format(parsed) : 'null';
};
