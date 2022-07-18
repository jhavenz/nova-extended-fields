<?php

namespace Jhavenz\NovaExtendedFields\Fields\Traits;

trait HasUniqueRule
{
    public string $table;
    public string $column = '{{resourceId}}';
    public ?string $connection;

    protected function addUniqueRule(array $rules): array
    {
        if (isset($this->table, $this->column)) {
            $rules[] = $this->uniqueRule();
        }

        return $rules;
    }

    public function on(string $connection): static
    {
        $this->connection = $connection;

        return $this;
    }

    public function setColumn(string $column): static
    {
        $this->column = $column;

        return $this;
    }

    public static function uniqueBy(string $table, ...$fieldArgs): static
    {
        return tap(static::make(...$fieldArgs), function ($self) use ($table) {
            $self->table = $table;
        });
    }

    protected function uniqueRule(): string
    {
        if (!isset($this->table, $this->column)) {
            throw new \LogicException(
                'Table and column properties must be specified. Instantiate using the [static::uniqueBy()] method'
            );
        }

        return str('unique:')
            ->when(isset($this->connection), fn($str) => $str->append($this->connection.'.'))
            ->append("{$this->table},{$this->column}");
    }
}
