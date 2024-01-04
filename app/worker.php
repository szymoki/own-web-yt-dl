<?php


interface YoutubeDownloaderInterface
{
  public function downloadVideo(string $url, string $dest);
  public function getFileName();
}

class YoutubeDlpDownloader implements YoutubeDownloaderInterface
{

  protected $filename = '';
  public function downloadVideo(string $url, string $dest)
  {
    $response = shell_exec('yt-dlp  -o "' . $dest . '%(title)s.%(ext)s"  --extract-audio --audio-format mp3 ' . $url);

    $tab = explode($dest, $response, 2);
    $tab = explode("\n", $tab[1]);
    $this->filename =  str_replace('.webm', '.mp3', $tab[0]);
  }

  public function getFileName()
  {
    return $this->filename;
  }
}

class OwnYtDlWorker
{

  private $downloadPath = 'music/';

  protected $data = [];

  private $downloaderClass = YoutubeDlpDownloader::class;

  private $downloaderInstance;

  public function getDownloaderInstance(): YoutubeDownloaderInterface
  {
    if (empty($this->downloaderInstance)) {
      $a = $this->downloaderClass;
      $this->downloaderInstance = new $a();
    }

    return  $this->downloaderInstance;
  }

  public function run()
  {
    while (true) {
      $this->nextTick();
      sleep(10);
    }
  }

  public function nextTick()
  {
    $this->readData();
    $tasks = $this->findNewTask();
    $this->processTasks($tasks);
  }

  private function processTasks($tasks)
  {
    foreach ($tasks as $task) {
      $this->proccesItem($task);
    }
  }

  private function findNewTask()
  {
    $new = [];
    foreach ($this->data as $item) {
      if ($item->status == 'new') {
        $new[] = $item;
      }
    }
    return $new;
  }

  private function proccesItem($item)
  {
    echo 'Proccess task ' . json_encode($item) . "\n";
    $this->updateStatus($item->id, 'working');
    $this->getDownloaderInstance()->downloadVideo($item->url, $this->downloadPath);
    $fileName =  $this->getDownloaderInstance()->getFileName();
    $clearedName = $this->clearName($fileName);
    rename($this->downloadPath . $fileName, $this->downloadPath . $clearedName);
    $this->updateStatus($item->id, 'done', $this->downloadPath . $clearedName);
  }

  private function  updateStatus($id, $status, $a = '')
  {
    $file = file_get_contents('music.data');
    $data = json_decode($file);
    foreach ($data as $item) {
      if ($item->id == $id) {
        $item->status = $status;
        $item->file = $a;
        file_put_contents('music.data', json_encode($data));
      }
    }
  }

  private function readData()
  {
    $file = file_get_contents('music.data');
    $this->data = json_decode($file);
  }

  private function clearName($name)
  {
    return str_replace(['#', ' ', '.', '(', ')', "'", '"', '&', '$', '%'], ['-', '-', '-', '-', '-', '', '', '', '', ''], $name . '"');
  }
}


$class  = new OwnYtDlWorker();
$class->run();
