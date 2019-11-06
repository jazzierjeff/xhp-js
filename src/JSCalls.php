<?hh //strict

class JSCalls{
    private vec<vec<mixed>> $calls = vec[];

    public function addCall(
        string $module,
        string $method,
        array<mixed> $args
    ) : void{
        $this->calls[] = vec[$module, $method, $args];
    }

    public function toJson() : string{
        return json_encode($this->calls);
    }
}