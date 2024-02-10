<?php
final class LSMTree
{
    private $memTable;
    private $ssTableList = [];
    private $maxSize;

    public function __construct($maxSize = 30)
    {
        $this->maxSize = $maxSize;
        $this->memTable = new MemTable();
    }

    public function put($key, $value)
    {
        $this->memTable->put($key, $value);

        if ($this->memTable->getSize() >= $this->maxSize) {
            $this->flushMemTable();
            $this->compact();
        }
    }

    public function get($key)
    {
        $value = $this->memTable->get($key);

        if ($value === null) {
            foreach ($this->ssTableList as $ssTable) {
                $value = $ssTable->get($key);

                if ($value !== null) {
                    return $value;
                }
            }
        }

        return $value;
    }

    public function delete($key)
    {
        $this->memTable->delete($key);
        $this->compact();
    }

    private function flushMemTable()
    {
        $ssTable = new SSTable($this->memTable->getData());
        $this->ssTableList[] = $ssTable;
        $this->memTable->clear();
    }

    private function compact()
    {
        $compactSSTableList = $this->ssTableList;
        $compactSSTableList[] = new SSTable($this->memTable->getData());

        $mergedData = [];
        foreach ($compactSSTableList as $ssTable) {
            $mergedData = array_merge($mergedData, $ssTable->getData());
        }
        ksort($mergedData);

        $compactSSTable = new SSTable($mergedData);
        $this->ssTableList = [$compactSSTable];
        $this->memTable->clear();
    }
}

final class MemTable
{
    private $data = [];

    public function put($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function delete($key)
    {
        unset($this->data[$key]);
    }

    public function getSize()
    {
        return count($this->data);
    }

    public function getData()
    {
        return $this->data;
    }

    public function clear()
    {
        $this->data = [];
    }
}

final class SSTable
{
    private $data = [];

    public function __construct($data)
    {
        $this->data = $data;
        $this->store();
    }

    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function getData()
    {
        return $this->data;
    }

    private function store()
    {
        $jsonData = json_encode($this->data, JSON_PRETTY_PRINT);
        file_put_contents('data.json', $jsonData);
    }
}

$lsmTree = new LSMTree(30);
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
$lsmTree->put('key-' . rand(), 'value');
