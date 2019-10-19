<?php

namespace Application\View;

include_once('ViewContract.php');

class RunView implements ViewContract
{
  private static $deleteRun =  __CLASS__ . '::deleteRun';
  private static $editRun =  __CLASS__ . '::editRun';
  private static $IDRun = __CLASS__ . '::IDRun';

  private $runs;

  public function __construct(array $runsToSave)
  {
    $this->updateRuns($runsToSave);
  }

  public function updateRuns(array $runs): void
  {
    $this->runs = $runs;
  }

  public function response(): string
  {
    if ($this->userWantsToCreateRun()) {
      return '';
    }

    $response = $this->generateTableHeader();
    $response .= $this->printRuns();

    return $response;
  }

  private function generateTableHeader(): string
  {
    return '
      <br>
      <table>
        <tr>
          <th>Distance</th>
          <th>Time</th>
          <th>Pace</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      ';
  }

  private function printRuns(): string
  {
    $output = "";

    if (!empty($this->runs)) {

      foreach (array_reverse($this->runs) as $run) {
        $output .= $this->generateFormStart();
        $output .= "<tr>";
        $output .= "<td>" . $run->getDistance() . " km" . "</td>";
        $output .= "<td>" . $run->getTime() . "</td>";
        $output .= "<td>" . $run->getPace() . " min/km" . "</td>";
        $output .= "<td>" . $run->getdate() . "</td>";
        $output .= "<td>" . $this->generateActionButton($run->getID()) . '</td>';
        $output .= "</tr>";
        $output .= $this->generateFormEnd();
      }
    } else {
      return $output .= '</table> <p> No runs so far.. </p>';
    }

    return $output . "</table>";
  }

  private function generateActionButton(int $id): string
  {
    return '
    <input name="' . self::$IDRun . '" type="hidden" value="' . $id . '" />
    <input name="' . self::$deleteRun . '" type="submit" value="Delete" />
    <input name="' . self::$editRun . '" type="submit" value="Edit" />
    ';
  }

  private function generateFormStart(): string
  {
    return '<form method="post" enctype="multipart/form-data">';
  }

  private function generateFormEnd(): string
  {
    return '</form>';
  }

  public function userWantsToEditRun(): bool
  {
    return isset($_POST[self::$editRun]);
  }

  public function getEditRun(): string
  {
    return self::$editRun;
  }

  public function userWantsToDeleteRun(): bool
  {
    return isset($_POST[self::$deleteRun]);
  }

  public function getRunId(): string
  {
    return $_POST[self::$IDRun];
  }

  public function userWantsToCreateRun(): bool
  {
    return isset($_GET['create']);
  }
}
