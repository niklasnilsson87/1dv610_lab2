<?php

namespace Application\View;

class RunView
{
  private static $deleteRun =  __CLASS__ . '::deleteRun';
  private static $editRun =  __CLASS__ . '::editRun';
  private static $IDRun = __CLASS__ . '::IDRun';

  private $runs;

  public function __construct(array $runsToSave)
  {
    $this->runs = $runsToSave;
  }

  public function response()
  {

    if ($this->userWantsToCreateRun()) {
      return '';
    }

    $response = $this->generateTableHeader();
    $response .= $this->printRuns();

    return $response;
  }

  public function generateTableHeader()
  {
    return '
      <br>
      <table>
        <tr>
          <th>Distance</th>
          <th>Time</th>
          <th>Pace</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
      ';
  }

  public function printRuns()
  {
    $output = "";

    if (!empty($this->runs)) {

      foreach ($this->runs as $run) {
        $output .= $this->generateFormStart();
        $output .= "<tr>";
        $output .= "<td>" . $run->getDistance() . " km" . "</td>";
        $output .= "<td>" . $run->getTime() . "</td>";
        $output .= "<td>" . $run->getPace() . " min/km" . "</td>";
        $output .= "<td>" . $run->getDescription() . "</td>";
        $output .= "<td>" . $this->generateActionButton($run->getID()) . '</td>';
        $output .= "</tr>";
        $output .= $this->generateFormEnd();
      }
    } else {
      return $output .= '</table> <p> No runs so far.. </p>';
    }

    return $output . "</table>";
  }

  private function generateActionButton($id): string
  {
    return '
    <input name="' . self::$IDRun . '" id="' . $id . '" type="hidden" value="' . $id . '" />
    <input name="' . self::$deleteRun . '" type="submit" value="Delete" />
    <input name="' . self::$editRun . '" type="submit" value="Edit" />
    ';
  }

  private function generateFormStart()
  {
    return '<form method="post" enctype="multipart/form-data">';
  }

  private function generateFormEnd()
  {
    return '</form>';
  }

  public function userWantsToEditRun()
  {
    return isset($_POST[self::$editRun]);
  }

  public function getEditRun()
  {
    return self::$editRun;
  }

  public function userWantsToDeleteRun()
  {
    return isset($_POST[self::$deleteRun]);
  }

  public function getRunId()
  {
    return $_POST[self::$IDRun];
  }

  public function userWantsToCreateRun()
  {
    return isset($_GET['create']);
  }
}
