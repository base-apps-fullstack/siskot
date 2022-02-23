<?php

namespace App\Traits\Model;

trait FindBy
{
    /**
     * Scope a query to only include by a given value.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindBy($query, $value)
    {
        return $query->where(function ($query) use ($value) {
            if (isset($this->findable)) {
                $firstFind = array_first($this->findable);

                if (isset($this->encrypted) && in_array($firstFind, $this->encrypted)) {
                    if ($firstFind == 'phone') {
                        $query->where(function ($q) use ($firstFind, $value) {
                            $q->where('phone', $value)->orWhere('phone', appencrypt($value));
                        });
                    } else {
                        $query->where($firstFind, appencrypt($value));
                    }
                } else {
                    $query->where($firstFind, $value);
                }

                for ($i = 1; $i < count($this->findable); $i++) {
                    if (isset($this->encrypted) && in_array($this->findable[$i], $this->encrypted)) {
                        $query->orWhere($this->findable[$i], appencrypt($value));
                    } else {
                        $query->orWhere($this->findable[$i], $value);
                    }
                }
            }
        });
    }
}
