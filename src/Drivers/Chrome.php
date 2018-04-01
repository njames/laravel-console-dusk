<?php

declare(strict_types=1);

namespace NunoMaduro\LaravelConsoleDusk\Drivers;

use Closure;
use Laravel\Dusk\Chrome\SupportsChrome;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use NunoMaduro\LaravelConsoleDusk\Contracts\Drivers\DriverContract;

class Chrome implements DriverContract
{
    use SupportsChrome;

    protected $downloadsPath;

    public function open(): void
    {
        static::startChromeDriver();
    }

    public function close(): void
    {
        static::stopChromeDriver();
    }

    public static function afterClass(Closure $callback): void
    {
        // ..
    }

    public function setDownloadsPath(string $path): void
    {
        $this->downloadsPath = $path;
    }

    public function getDriver()
    {
        $options = (new ChromeOptions)->addArguments(
            [
                '--disable-gpu',
                '--headless',
            ]
        );

        $options->setExperimentalOption('prefs', ['download.default_directory' => $this->downloadsPath]);

        return RemoteWebDriver::create(
            'http://localhost:9515',
            DesiredCapabilities::chrome()
                ->setCapability(
                    ChromeOptions::CAPABILITY,
                    $options
                )
        );
    }

    public function __destruct()
    {
        $this->close();
    }
}
