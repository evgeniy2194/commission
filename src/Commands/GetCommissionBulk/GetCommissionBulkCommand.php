<?php

namespace App\Commands\GetCommissionBulk;

use App\Commands\BaseCommand;
use App\Commands\CommandInterface;
use App\Services\Commission\CommissionsService;
use App\Services\CountryLocation\BinlistLocationService;
use App\Services\ExchangeRate\ApilayerService;
use Exception;
use GuzzleHttp\Client;

class GetCommissionBulkCommand extends BaseCommand implements CommandInterface
{
    private CommissionsService $commissionService;

    public function __construct()
    {
        $this->commissionService = new CommissionsService(
            new ApilayerService(new Client()),
            new BinlistLocationService(new Client())
        );
    }

    public function execute()
    {
        $args = $this->getArguments();
        $filename = $args[1];

        if (!file_exists($filename)) {
            return $this->output("File doesn't exists");
        }

        $content = file_get_contents($filename);
        $commissions = [];

        foreach (explode("\n", $content) as $row) {
            $data = json_decode($row, true);

            if (!$data || sizeof($data) < 3) {
                $commissions[] = "Invalid input data";
                continue;
            }

            try {
                $commission = $this->commissionService->getSumWithCommission(
                    intval($data["bin"]),
                    floatval($data["amount"]),
                    $data["currency"]
                );
                $commissions[] = $commission;
            } catch (Exception $e) {
                $commission[] = "Error: " . $e->getMessage();
            }
        }

        return $this->output(join("\n", $commissions));
    }
}