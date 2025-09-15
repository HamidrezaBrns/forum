<script setup lang="ts">
import { CategoryScale, Chart as ChartJS, Filler, Legend, LinearScale, LineElement, PointElement, Title, Tooltip } from 'chart.js';
import { Line } from 'vue-chartjs';

ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale, Filler);

const props = defineProps<{
    title: string;
    labels: string[];
    data: number[];
    color?: string;
    bgColor?: string;
}>();

const chartData = {
    labels: props.labels,
    datasets: [
        {
            label: props.title,
            data: props.data,
            borderColor: props.color ?? '#888',
            backgroundColor: props.bgColor ?? '#555',
            tension: 0.3,
        },
    ],
};

const chartOptions = {
    responsive: true,
    plugins: {
        legend: {
            display: false,
        },
        title: {
            display: true,
            text: props.title,
        },
    },
};
</script>

<template>
    <div>
        <Line :data="chartData" :options="chartOptions" />
    </div>
</template>
