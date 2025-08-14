import { formatDistance, parseISO } from 'date-fns';

const relativeDate = (date) => formatDistance(parseISO(date), new Date(), { addSuffix: true });

const formattedDate = () =>
    new Date().toLocaleString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });

export { formattedDate, relativeDate };
