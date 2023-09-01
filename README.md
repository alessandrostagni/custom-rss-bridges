# custom-rss-bridges
A collection of custom rss bridges for [RSS Bridge](https://github.com/RSS-Bridge/rss-bridge)

## How to use PhantomJS in your PHP bridges

Sometimes you need to wait for pages to be fully loaded in order to parse them correctly.
This is not possible by using PHP natively, as it is a server side language.

One option is to use the headless PhantomJS browser, along with the PHP interface that's been developed for it.

I managed to install PhantomJS correctly on Amazon Linux AMI 2.
I had to use [composer 1.9](https://getcomposer.org/download/1.9.0/composer.phar) to install the correct version of PhantomJS and the PHP interface by following these [instructions](https://jonnnnyw.github.io/php-phantomjs/)

For reference, here is an example snippet. <br/>
Specifically, you can read these [docs](https://jonnnnyw.github.io/php-phantomjs/4.0/3-usage/#on-load-finished) on how to wait for a page to be fully loaded.

```
$client = Client::getInstance();
$client->isLazy();
$client->getEngine()->setPath('"PATH_TO_YOUR_PHANTOMJS_EXECUTABLE");
$client->getEngine()->addOption('--ssl-protocol=any');
$client->getEngine()->addOption('--ignore-ssl-errors=true');
$request = $client->getMessageFactory()->createRequest('https://google.com');
$request->setTimeout(5000);
$request->setRequestData(array('offset' => '10'));
$response = $client->getMessageFactory()->createResponse();
$client->send($request, $response);
$dom = str_get_html($response->getContent());
```

I **strongly** recommend of testing HTTP and HTTPS requests through the use of an [example script](https://github.com/ariya/phantomjs/blob/master/examples/post.js).
99% of the time PHP won't throw any error and just show an empty string as value returned by any of the PhantomJS calls.

### Note

PhantomJS is [no longer maintained](https://www.puzzle.ch/de/blog/articles/2018/02/12/phantomjs-is-dead-long-live-headless-browsers#:~:text=As%20of%20spring%202017%2C%20PhantomJS,PhantomJS%2C%20the%20headless%20WebKit%20browser.), so if you have better suggestions available for PHP that are not too heavy for a small self-hosted service, feel free to open an Issue or even a PR ;).
