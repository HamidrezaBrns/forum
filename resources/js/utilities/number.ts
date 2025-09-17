// utils/number.ts
/**
 * Format number based on current locale
 */
export function formatNumber(
    num: number | string,
    options: object = {
        notation: 'compact',
        maximumFractionDigits: 1,
    },
    locale: string = document.documentElement.lang,
): string {
    return new Intl.NumberFormat(locale, options).format(Number(num));
}

/**
 * Force Persian digits
 */
export function toPersianDigits(num: number | string): string {
    return num.toString().replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[parseInt(d)]);
}

/**
 * Force English digits
 */
export function toEnglishDigits(num: number | string): string {
    return num.toString().replace(/[۰-۹]/g, (d) => '0123456789'['۰۱۲۳۴۵۶۷۸۹'.indexOf(d)]);
}
