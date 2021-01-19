<?php
/*
 * sysPass
 *
 * @author nuxsmin
 * @link https://syspass.org
 * @copyright 2012-2021, Rubén Domínguez nuxsmin@$syspass.org
 *
 * This file is part of sysPass.
 *
 * sysPass is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * sysPass is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 *  along with sysPass.  If not, see <http://www.gnu.org/licenses/>.
 */

use Psr\Container\ContainerInterface;
use SP\Modules\Cli\Commands\InstallCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function DI\autowire;
use function DI\create;
use function DI\factory;

return [
    Application::class => create(Application::class),
    OutputInterface::class => create(ConsoleOutput::class)
        ->constructor(ConsoleOutput::VERBOSITY_NORMAL, true),
    InputInterface::class => create(ArgvInput::class),
    SymfonyStyle::class => factory(function (ContainerInterface $c) {
        return new SymfonyStyle(
            $c->get(InputInterface::class),
            $c->get(OutputInterface::class)
        );
    }),
    InstallCommand::class => autowire()
];
