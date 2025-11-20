import clsx from 'clsx';
import Heading from '@theme/Heading';
import styles from './styles.module.css';
import Link from "@docusaurus/Link";

export default function HomepageFeatures() {
  return (
    <section className={styles.features}>
      <div className="container">
        <div className="row">
            <div className={clsx("col col--8")}>
                <Heading as="h1">Introduction</Heading>
                <p>Lmc Authentication offers middleware to implement authentication in your
                    application.</p>
                <p>Lmc Authentication works in the same way as
                    <Link href='https://docs.mezzio.dev/mezzio-authentication'>Mezzio Authentication</Link>
                    except that it does not perform any action when authentication does result into a user.
                </p>
                <Heading as="h2">Support</Heading>
                <ul>
                    <li>File issues at <Link
                        href="https://github.com/LM-Commons/lmc-authentication/issues">
                        github.com/LM-Commons/lmc-authentication/issues</Link>.
                    </li>
                    <li>Ask questions in the <a
                        href="https://discord.gg/MSQZQJcS4S">LM-Commons
                        Discord</a> chat.
                    </li>
                </ul>
            </div>
        </div>
      </div>
    </section>
  );
}
