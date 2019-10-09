<?php

namespace Application\View;

class RunView
{

  private $runs;

  public function __construct(\Application\Model\RunStorage $storage)
  {
    $this->runs = $storage->getRuns();
  }

  public function response()
  {
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
        </tr>
      ';
  }

  public function printRuns()
  {
    $output = "";

    if (!empty($this->runs)) {

      foreach ($this->runs as $run) {
        $output .= "<tr>";
        $output .= "<td>" . $run->getDistance() . "</td>";
        $output .= "<td>" . $run->getTime() . "</td>";
        $output .= "<td>" . $run->getPace() . "</td>";
        $output .= "<td>" . $run->getDescription() . "</td>";
        $output .= "</tr>";
      }
    } else {
      return $output .= '</table> <p> No runs so far.. </p>';
    }

    return $output . "</table>";
  }
}
