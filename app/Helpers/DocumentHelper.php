<?php

namespace App\Helpers;

class DocumentHelper
{
    /**
     * Gera um CPF ou CNPJ válido conforme o tipo do usuário.
     *
     * @param string $userType 'common' ou 'merchant'
     * @return string
     */
    public static function generate(string $userType = 'common'): string
    {
        if (strtolower($userType) === 'merchant') {
            return self::generateCnpj();
        }

        return self::generateCpf();
    }

    /**
     * Gera um CPF válido.
     *
     * @return string
     */
    public static function generateCpf(): string
    {
        $cpfDigits = self::generateCpfDigits(); // Obtenha os números do CPF
        return self::applyCpfMask($cpfDigits);  // Aplicar máscara
    }

    /**
     * Gera um CNPJ válido.
     *
     * @return string
     */
    public static function generateCnpj(): string
    {
        $cnpjDigits = self::generateCnpjDigits(); // Obtenha os números do CNPJ
        return self::applyCnpjMask($cnpjDigits);  // Aplicar máscara
    }

    /**
     * Gera os 12 primeiros números do CNPJ (sem máscara).
     *
     * @return string
     */
    private static function generateCnpjDigits(): string
    {
        $numbers = [];
        for ($i = 0; $i < 12; $i++) {
            $numbers[] = rand(0, 9);
        }
        $numbers[12] = self::calculateCnpjDigit($numbers);
        $numbers[13] = self::calculateCnpjDigit($numbers);

        return implode('', $numbers);
    }

    /**
     * Aplica a máscara ao número do CNPJ.
     *
     * @param string $cnpj
     * @return string
     */
    private static function applyCnpjMask(string $cnpj): string
    {
        return vsprintf('%s%s.%s%s%s.%s%s%s/%s%s%s%s-%s%s', str_split($cnpj));
    }

    /**
     * Gera os 9 primeiros números do CPF (sem máscara).
     *
     * @return string
     */
    private static function generateCpfDigits(): string
    {
        $numbers = [];
        for ($i = 0; $i < 9; $i++) {
            $numbers[] = rand(0, 9);
        }
        $numbers[9] = self::calculateCpfDigit($numbers, 10);
        $numbers[10] = self::calculateCpfDigit($numbers, 11);

        return implode('', $numbers);
    }

    /**
     * Aplica a máscara ao número do CPF.
     *
     * @param string $cpf
     * @return string
     */
    private static function applyCpfMask(string $cpf): string
    {
        return vsprintf('%s%s%s.%s%s%s.%s%s%s-%s%s', str_split($cpf));
    }

    /**
     * Calcula o dígito verificador do CPF.
     *
     * @param array $numbers
     * @param int $weight
     * @return int
     */
    private static function calculateCpfDigit(array $numbers, int $weight): int
    {
        $sum = 0;
        for ($i = 0; $i < $weight - 1; $i++) {
            $sum += $numbers[$i] * ($weight - $i);
        }

        $rest = $sum % 11;
        return $rest < 2 ? 0 : 11 - $rest;
    }

    /**
     * Calcula o dígito verificador do CNPJ.
     *
     * @param array $numbers
     * @return int
     */
    private static function calculateCnpjDigit(array $numbers): int
    {
        $weights = count($numbers) === 12
            ? [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]
            : [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        $sum = 0;
        foreach ($numbers as $i => $number) {
            $sum += $number * $weights[$i];
        }

        $rest = $sum % 11;
        return $rest < 2 ? 0 : 11 - $rest;
    }
}
