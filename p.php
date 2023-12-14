<?php

/**
 * Perform Pulse Code Modulation (PCM) encoding on an analog signal.
 *
 * @param array $analog_signal The input analog signal.
 * @param int   $bit_depth     The number of bits used for quantization.
 *
 * @return array The PCM samples.
 */
function pcm_encode($analog_signal, $bit_depth = 8) {
    $max_amplitude = pow(2, $bit_depth) / 2 - 1;
    $samples = [];

    foreach ($analog_signal as $value) {
        // Ensure the input value is numeric
        if (!is_numeric($value)) {
            throw new InvalidArgumentException("Invalid input value: $value. Numeric values expected.");
        }

        // Ensure the value is within the valid range
        $value = max(min($value, $max_amplitude), -$max_amplitude);

        // Scale the value to fit within the quantization levels
        $scaled_value = round(($value + $max_amplitude) / (2 * $max_amplitude) * pow(2, $bit_depth - 1));

        // Convert the scaled value to binary and store in the samples array
        $samples[] = str_pad(decbin($scaled_value), $bit_depth, '0', STR_PAD_LEFT);
    }

    return $samples;
}

/**
 * Perform Pulse Code Modulation (PCM) decoding on PCM samples.
 *
 * @param array $pcm_samples The input PCM samples.
 * @param int   $bit_depth   The number of bits used for quantization.
 *
 * @return array The decoded analog signal.
 */
function pcm_decode($pcm_samples, $bit_depth = 8) {
    $max_amplitude = pow(2, $bit_depth) / 2 - 1;
    $analog_signal = [];

    foreach ($pcm_samples as $binary_value) {
        // Ensure the input value is a binary string
        if (!preg_match('/^[01]{' . $bit_depth . '}$/', $binary_value)) {
            throw new InvalidArgumentException("Invalid binary value: $binary_value. Expected $bit_depth bits.");
        }

        // Convert the binary value to decimal
        $scaled_value = bindec($binary_value);

        // Scale the value back to the original range
        $scaled_value = ($scaled_value / (pow(2, $bit_depth - 1))) - 1;

        // Rescale to the original amplitude range
        $analog_signal[] = $scaled_value * $max_amplitude;
    }

    return $analog_signal;
}

// Example usage:
try {
    $analog_signal = [0.5, -0.2, 0.8, -0.6];
    $bit_depth = 8;

    $pcm_samples = pcm_encode($analog_signal, $bit_depth);
    $decoded_analog_signal = pcm_decode($pcm_samples, $bit_depth);

    // Display results
    echo "Original Analog Signal: " . implode(', ', $analog_signal) . PHP_EOL;
    echo "PCM Samples: " . implode(', ', $pcm_samples) . PHP_EOL;
    echo "Decoded Analog Signal: " . implode(', ', $decoded_analog_signal) . PHP_EOL;
} catch (InvalidArgumentException $e) {
    // Handle invalid arguments exception
    echo "Error: " . $e->getMessage() . PHP_EOL;
} catch (Exception $e) {
    // Handle other exceptions
    echo "An unexpected error occurred: " . $e->getMessage() . PHP_EOL;
}


?>