<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCM Graph</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>PCM Encoding Graph</h2>
    <canvas id="pcmGraph" width="400" height="200"></canvas>

    <script>
        // Sample PCM encoding function
        function pcm_encode(analog_signal, bit_depth = 8) {
            const max_amplitude = Math.pow(2, bit_depth) / 2 - 1;
            const samples = [];

            analog_signal.forEach(value => {
                value = Math.max(Math.min(value, max_amplitude), -max_amplitude);
                const scaledValue = Math.round((value + max_amplitude) / (2 * max_amplitude) * Math.pow(2, bit_depth - 1));
                samples.push(scaledValue);
            });

            return samples;
        }

        // Sample data
        const analogSignal = [0.5, -0.2, 0.8, -0.6];
        const bitDepth = 8;

        // PCM encode the sample data
        const pcmSamples = pcm_encode(analogSignal, bitDepth);

        // Set up the Chart.js graph
        const ctx = document.getElementById('pcmGraph').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: pcmSamples.map((_, index) => `Sample ${index + 1}`),
                datasets: [{
                    label: 'PCM Samples',
                    data: pcmSamples,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    pointRadius: 5,
                    pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                }],
            },
            options: {
                scales: {
                    x: {
                        type: 'linear',
                        position: 'bottom',
                    },
                    y: {
                        min: -Math.pow(2, bitDepth) / 2,
                        max: Math.pow(2, bitDepth) / 2,
                    },
                },
            },
        });
    </script>
</body>
</html>
