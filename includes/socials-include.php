<?php
require_once 'datenbank.php';

$mobileSocials = ''; // HTML-Ausgabe vorbereiten

$stmt = $pdo->prepare("SELECT `plattform`, `url` FROM `socials` WHERE `wichtigkeit` = 1 ORDER BY `reihenfolge` ASC");
$stmt->execute();
$socials = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($socials) {
    $mobileSocials .= '<div class="mobile-socials">';
    foreach ($socials as $social) {
        $plattform = strtolower($social['plattform']);
        $url = htmlspecialchars($social['url']);
        $icon = '';

        switch ($plattform) {
            case 'instagram':
                $icon = '<i class="fa-brands fa-instagram fa-xl"></i>';
                break;
            case 'facebook':
                $icon = '<i class="fa-brands fa-square-facebook fa-xl"></i>';
                break;
            case 'twitter':
                $icon = '<i class="fa-brands fa-square-x-twitter fa-xl"></i>';
                break;
            case 'youtube':
                $icon = '<i class="fa-brands fa-youtube fa-xl"></i>';
                break;
            case 'tiktok':
                $icon = '<i class="fa-brands fa-tiktok fa-lg"></i>';
                break;
            case 'pinterest':
                $icon = '<i class="fa-brands fa-square-pinterest fa-xl"></i>';
                break;
            default:
                $icon = '<i class="fa-solid fa-hashtag fa-lg"></i>';
              }

              $mobileSocials .= '<div class="social-icon"><a href="' . $url . '" target="_blank" rel="noopener noreferrer">' . $icon . '</a></div>';
          }
          $mobileSocials .= '</div>';
      }