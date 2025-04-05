<?php

namespace App\Interfaces;

interface TransferRepositoryInterface
{

    function deleteTransferWithId(int $transferId);
    function getTransferWithId(int $transferId);
    function createTransfer(array $data);

}