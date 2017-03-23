<?php
namespace Blog\Command;

use DI\Annotation\Inject;
use DI\Container;
use Doctrine\DBAL\Connection;
use Silly\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class GenerateMetaCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:generate_meta')
            ->setDescription('create some default articles');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = app(Container::class);
        $keys      = array_keys(require $container['app.root'] . '/config/container.php');

        $string = [];
        foreach ($keys as $key) {
            $type = gettype($container[$key]);
            if ($type == 'object') {
                $class    = get_class($container[$key]);
                $key      = addslashes($key);
                $string[] = "            \"{$key}\"  => \\{$class}::class,";
            }
        }
        $metas    = join(PHP_EOL, $string);
        $metaFile = "
<?php
namespace PHPSTORM_META {
    override(
        \\DI\\Container::get(0),
        map([
            \"\"   => \"@\",
$metas
        ]));
    override(
        \\app(0),
        map([
            \"\"   => \"@\",
$metas
        ]));
}
";
        app(Filesystem::class)->dumpFile("{$container['app.root']}/.phpstorm.meta.php/php-di.meta.php", $metaFile);
    }

}