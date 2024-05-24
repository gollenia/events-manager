import { useEffect, useMemo, useState } from "@wordpress/element";

function useMediaQuery(query: string) {
	const mediaQuery = useMemo(() => window.matchMedia(query), [query]);
	const [match, setMatch] = useState(mediaQuery.matches);
  
	useEffect(() => {
	  const onChange = () => setMatch(mediaQuery.matches);
	  mediaQuery.addEventListener("change", onChange);
  
	  return () => mediaQuery.removeEventListener("change", onChange);
	}, [mediaQuery]);
  
	return match;
  }

function useMediaQueries() {
	const md = useMediaQuery("(min-width: 800px)");
	const lg = useMediaQuery("(min-width: 1200px)");

	return { md, lg };
}

export default useMediaQueries;