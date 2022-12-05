export function baseUrl(url: string) {
  if (url[0] === '/')
    url = url.substring(1)

  return `${import.meta.env.VITE_API_URL}/${url}`
}

export function fileToBase64(file: File) {
  return new Promise<string>((resolve, reject) => {
    const f = new FileReader();

    f.onloadend = (e) => {
      const result = e.target?.result;
      if (typeof result !== 'string') {
        reject('Can\'t read file');
        return;
      }

      resolve(result);
    }

    f.readAsDataURL(file);
  });
}
