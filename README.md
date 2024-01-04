# own-web-yt-dl

The own-web-yt-dl project allows you to download audio files from YouTube as MP3s and provides access to them through a web interface. You can also mount the `music` folder as a volume to store the downloaded files.

## Building the Image

To build the image, execute the following command in the directory containing the Dockerfile:

```bash
docker build -t own-web-yt-dl .
```

## Running the Container

### With a Single Port and Attached Volume

To run the container, redirecting port 8100 to the web interface and attaching the music volume, execute:


```bash
docker run -d -p 8100:80 -v "$(pwd)"/music:/app/music own-web-yt-dl
```

This command starts the container and makes the web interface accessible at http://localhost:8100. All downloaded audio files will be stored in the music folder on your host.


## Additional Options
You can customize ports, image names, volume directories, and other options as needed. Make sure to adjust the supervisord.conf file or other configuration files according to your own-web-yt-dl project 
