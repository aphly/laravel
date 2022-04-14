<?php

namespace Aphly\Laravel\Libs\Snowflake;

interface SequenceResolver
{
    /**
     * The snowflake.
     *
     * @param int|string $currentTime current request ms
     *
     * @return int
     */
    public function sequence(int $currentTime);
}
