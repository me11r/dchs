<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 07.07.2018
 * Time: 14:32
 */

namespace App\Twigbridge\Extension\Webpack;


use Illuminate\Routing\UrlGenerator;
use RuntimeException;
use Twig_SimpleFunction;

class Manifest extends \Twig_Extension
{
    protected $url;
    protected $manifest;

    /**
     * Manifest constructor.
     * @param $url
     */
    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
        $manifest_filename = public_path(env('MANIFEST_URL', 'assets/manifest.json'));
        if (!file_exists($manifest_filename)) {
            throw new RuntimeException('Manifest file not found at ' . $manifest_filename);
        }
        $this->manifest = json_decode(file_get_contents($manifest_filename, false), true);
    }

    public function manifested(string $url): string
    {
        $url = $this->manifest[$url] ?? $url;
        return $this->url->asset($url);
    }


    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('manifest', [$this, 'manifested'], ['is_safe' => ['html']]),
        ];
    }


}