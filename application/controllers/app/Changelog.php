<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Changelog extends CI_Controller
{
    private $githubApiUrl = 'https://api.github.com/repos/muhammadnorseputra/renkeu/commits?sha=middle-dev&per_page=30';
    private $cacheKey = 'github_changelog_muhammadnorseputra_renkeu_middle_dev';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        $this->load->driver('cache', ['adapter' => 'file']);
    }

    public function index()
    {
        $cachedCommits = $this->cache->get($this->cacheKey);
        if ($cachedCommits !== false && is_array($cachedCommits)) {
            $commits = $cachedCommits;
            $error = null;
        } else {
            $result = $this->fetchCommits();
            $commits = $result['commits'];
            $error = $result['error'];

            if ($error === null && !$this->cache->save($this->cacheKey, $commits, 300)) {
                log_message('error', 'Unable to cache GitHub changelog response.');
            }
        }

        usort($commits, function ($first, $second) {
            return strtotime($second['date_iso']) <=> strtotime($first['date_iso']);
        });

        $data = [
            'title' => 'Changelog',
            'content' => 'pages/changelog',
            'commits' => $commits,
            'error' => $error,
            'autoload_css' => [
                'template/custom-css/changelog.css',
            ],
        ];

        $this->load->view('layout/app', $data);
    }

    private function fetchCommits()
    {
        if (!function_exists('curl_init')) {
            log_message('error', 'cURL is not available for the GitHub changelog request.');
            return [
                'commits' => [],
                'error' => 'Fitur pengambilan changelog tidak tersedia di server ini.',
            ];
        }

        $curl = curl_init($this->githubApiUrl);
        if ($curl === false) {
            log_message('error', 'Unable to initialize cURL for the GitHub changelog request.');
            return [
                'commits' => [],
                'error' => 'Changelog GitHub tidak dapat dimuat. Silakan coba lagi nanti.',
            ];
        }

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 12,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER => [
                'Accept: application/vnd.github+json',
                'X-GitHub-Api-Version: 2022-11-28',
            ],
            CURLOPT_USERAGENT => 'Emonev-Changelog',
        ]);

        $response = curl_exec($curl);
        if ($response === false) {
            $curlError = curl_error($curl);
            curl_close($curl);
            log_message('error', 'GitHub changelog request failed: ' . $curlError);

            return [
                'commits' => [],
                'error' => 'Changelog GitHub tidak dapat dimuat. Silakan coba lagi nanti.',
            ];
        }

        $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpStatus < 200 || $httpStatus >= 300) {
            log_message('error', 'GitHub changelog API returned HTTP ' . $httpStatus . '.');
            return [
                'commits' => [],
                'error' => $httpStatus === 403
                    ? 'GitHub sedang membatasi permintaan. Silakan coba lagi beberapa menit.'
                    : 'GitHub tidak dapat memuat changelog saat ini. Silakan coba lagi nanti.',
            ];
        }

        $responseData = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($responseData)) {
            log_message('error', 'GitHub changelog API returned invalid JSON.');
            return [
                'commits' => [],
                'error' => 'Data changelog dari GitHub tidak dapat dibaca.',
            ];
        }

        $commits = [];
        foreach ($responseData as $item) {
            if (
                !is_array($item) ||
                !isset($item['commit']['message']) ||
                !is_string($item['commit']['message'])
            ) {
                continue;
            }

            $date = isset($item['commit']['committer']['date'])
                ? $item['commit']['committer']['date']
                : (isset($item['commit']['author']['date']) ? $item['commit']['author']['date'] : '');
            if (!is_string($date) || $date === '') {
                continue;
            }

            $timestamp = strtotime($date);
            if ($timestamp === false) {
                continue;
            }

            $messageLines = preg_split('/\r\n|\r|\n/', trim($item['commit']['message']));
            $subject = trim(array_shift($messageLines));
            if ($subject === '') {
                continue;
            }

            $sha = isset($item['sha']) && is_string($item['sha']) && preg_match('/^[a-f0-9]{40}$/i', $item['sha'])
                ? strtolower($item['sha'])
                : '';
            $author = isset($item['author']['login']) && is_string($item['author']['login']) && $item['author']['login'] !== ''
                ? $item['author']['login']
                : (isset($item['commit']['author']['name']) && is_string($item['commit']['author']['name'])
                    ? $item['commit']['author']['name']
                    : 'Kontributor');
            $commitUrl = isset($item['html_url']) &&
                is_string($item['html_url']) &&
                strpos($item['html_url'], 'https://github.com/muhammadnorseputra/renkeu/commit/') === 0
                ? $item['html_url']
                : '';

            $commits[] = [
                'sha' => $sha,
                'short_sha' => $sha !== '' ? substr($sha, 0, 7) : 'commit',
                'url' => $commitUrl,
                'author' => $author,
                'subject' => $subject,
                'description' => trim(implode("\n", $messageLines)),
                'date_iso' => date('c', $timestamp),
                'date_label' => date('d/m/Y', $timestamp),
                'time_label' => date('H:i', $timestamp),
                'month_key' => date('Y-m', $timestamp),
                'month_label' => $this->getMonthLabel($timestamp),
            ];
        }

        return [
            'commits' => $commits,
            'error' => null,
        ];
    }

    private function getMonthLabel($timestamp)
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $months[(int) date('n', $timestamp)] . ' ' . date('Y', $timestamp);
    }
}
