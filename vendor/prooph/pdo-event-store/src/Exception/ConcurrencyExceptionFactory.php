<?php
/**
 * This file is part of the prooph/pdo-event-store.
 * (c) 2016-2018 prooph software GmbH <contact@prooph.de>
 * (c) 2016-2018 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\EventStore\Pdo\Exception;

use Prooph\EventStore\Exception\ConcurrencyException;

class ConcurrencyExceptionFactory
{
    public static function fromStatementErrorInfo(array $errorInfo): ConcurrencyException
    {
        return new ConcurrencyException(
            \sprintf(
                "Some of the aggregate IDs or event IDs have already been used in the same stream. The PDO error should contain more information:\nError %s.\nError-Info: %s",
                $errorInfo[0],
                $errorInfo[2]
            )
        );
    }
}
