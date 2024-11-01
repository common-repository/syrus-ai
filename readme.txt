=== Syrus AI ===
Contributors: Daniele Di Rollo, Paolo Mondillo, Cristiano De Luca, Andrea Carlizza, Marco Lorica
Tags: AI
Donate link: https://syrusindustry.com
Stable Tag: 0.4.3
Requires at least: 5.2
Tested up to: 6.4
Requires PHP: 8.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==
Syrus AI is a cutting-edge WordPress plugin that offers users advanced artificial intelligence tools. This innovative plugin integrates AI capabilities into WordPress, providing users with advanced features and intelligent automations. From automated content generation, Syrus AI revolutionizes the interaction between WordPress sites and their owners, becoming an essential tool for those who want to fully harness the potential of artificial intelligence for their online presence.

Unordered list:

* Newsmaking
* Get articles from another Wordpress, translate and publish
* ChatGPT integration
* Image generator
* Content generator
* Video generator
* Slides generator
* Link matrix

== Installation ==
1. Create a folder named 'syrus-ai' inside /wp-content/plugins.
2. Move the files 'syrus-ai.php' and 'load.php' into the newly created folder
3. Next, move the folders named 'inc,' 'assets,' and 'admin' into the 'syrus-ai' folder you just created
4. Go to the plugins page on WordPress, find 'syrus-ai,' and activate it
5. Now the plugin has been successfully installed. Have fun harnessing artificial intelligence within WordPress!"

== Frequently Asked Questions ==


== Screenshots ==

== Changelog ==


== Upgrade Notice ==


== External Services Info & Usage ==
Our WordPress plugin represents a cutting-edge in connectivity and security, thanks to the adoption of a centralized approach that involves the use of a proprietary server to manage all the functionalities of the plugin. This model allows us to achieve optimal levels of speed and security, standing out in the WordPress plugin landscape. Connecting to our server eliminates the plugin's direct dependency on other external services, resulting in enhanced security and simplified maintenance. Furthermore, this architecture allows us to concentrate all the operations and logics of the plugin in a single controlled location, thus ensuring a smooth and secure user experience.
One of the key aspects of our plugin is the interaction with the server, which occurs exclusively at the user's request. This means that there are no background processes or data exchanges happening without the explicit consent of the user, thereby respecting privacy and transparency in data usage. Data security is an absolute priority: during communications, the only data transmitted is the installation domain of the plugin, which is crucial for authentication with our APIs. For each installation, we generate a unique and encrypted token that ensures a protected and secure data transfer, significantly reducing the risk of security breaches.
Communication between the plugin and our server takes place through the REST protocol, which is a de facto standard for modern APIs due to its simplicity, efficiency, and flexibility. Data is exchanged in JSON format, preferred for its readability and ease of integration with a wide range of programming languages. This choice ensures not only efficient communication but also greater compatibility with different systems and technologies.
The security of authentication is guaranteed through a system of unique tokens for each plugin installation. This approach ensures that every interaction with our server is authenticated and secure, offering a level of customization and security that goes beyond standard solutions. Moreover, we have implemented a robust error and timeout management system within our APIs, ensuring that any potential connection or response issues are handled effectively and transparently, minimizing interruptions for the end user.
A fundamental aspect of our solution is performance optimization. We have adopted measures to minimize the number of necessary requests and to ensure a rapid and reliable response from our server. Our infrastructure is highly scalable, capable of quickly adapting to a constantly changing volume of requests, thus ensuring service continuity even under high load conditions. This not only improves the user experience but also ensures efficient resource management.
We are strongly committed to the continuous improvement of our plugin and firmly believe in the value of feedback and collaboration. Therefore, we actively invite the community of users and developers to share their suggestions, ideas, and proposals for improvement with us. Our door is always open to contributions from the community, which we consider a fundamental resource for development and innovation. Through this collaborative exchange, we commit to maintaining and enhancing the quality and effectiveness of our plugin, ensuring that it remains cutting-edge and dynamically responds to the ever-evolving needs of the digital landscape.

1. https://developers.syrus.it/api/wp/v1/newsmaking
Usage: This endpoint is designed for sourcing articles suitable for the newsmaking section of a plugin. It specializes in fetching content that is current and relevant, ideal for users looking to populate their platforms with timely news-related material.
Terms of Use: https://syrusindustry.com/wordpress/plugin/syrus-ai/terms-of-use

2. https://api.openai.com/v1/chat/completions
Usage: Utilized for querying OpenAI's APIs for text generation, this endpoint currently supports creating texts for articles and social media posts. Plans are in place to gradually phase out this endpoint in favor of integrating these functionalities directly into our proprietary APIs.
Terms of Use: https://openai.com/policies/terms-of-use

3. https://developers.syrus.it/api/wp/v1/import/configuration
Usage: Usage: This endpoint facilitates the importation of plugin configurations from one blog installation to another. It's a key tool for users who manage multiple blogs, allowing them to easily replicate plugin settings across different sites.
Terms of Use: https://syrusindustry.com/wordpress/plugin/syrus-ai/terms-of-use

4. https://developers.syrus.it/api/wp/v1/instagram/revoke
Usage: Dedicated to revoking permission tokens generated by users for posting on Instagram. This endpoint is critical for ensuring user security and control, enabling the management of Instagram account access through the plugin.
Terms of Use: https://syrusindustry.com/wordpress/plugin/syrus-ai/terms-of-use

5. https://developers.syrus.it/api/wp/v1/check_token
Usage: his endpoint is used to verify the number of tokens consumed in supplying prompts to OpenAI. It's vital for tracking and regulating the use of API resources, ensuring efficient and accountable usage of the system.
Terms of Use: https://syrusindustry.com/wordpress/plugin/syrus-ai/terms-of-use
