# Użyj obrazu PHP 8
FROM php:8

# Zainstaluj niezbędne zależności
RUN apt-get update && \
  apt-get install -y \
  ffmpeg \
  curl \
  wget \
  python3 \
  supervisor

# Pobierz yt-dlp z najnowszego wydania
RUN wget https://github.com/yt-dlp/yt-dlp/releases/latest/download/yt-dlp_linux -O /usr/local/bin/yt-dlp && \
    chmod a+rx /usr/local/bin/yt-dlp


# Skopiuj plik konfiguracyjny supervisorda
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Utwórz katalog roboczy
WORKDIR /app

# W razie potrzeby, skopiuj pliki Twojego projektu do katalogu /app
COPY ./app /app

# Określ, jak ma zostać uruchomiony supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
