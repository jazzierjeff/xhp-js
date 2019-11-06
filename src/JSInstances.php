<?hh //strict

class JSInstances{
    private vec<vec<mixed>> $instances = vec[];

    public function addInstance(
        string $module,
        string $method,
        array<mixed> $args
    ) : void{
        $this->instances[] = vec[$module, $method, $args];
    }

    public function toJson() : string{
        return json_encode($this->instances);
    }
}